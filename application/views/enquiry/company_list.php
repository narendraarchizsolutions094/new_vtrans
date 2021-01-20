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
		<div class="panel panel-success">
			<div class="panel-body">
                    <table style="width: 100%" id="companyTable" class="datatable1 table table-bordered table-response">
                         <thead>
                                  
                             <tr>
                                     <th>&nbsp; # &nbsp;</th>
                                     <th><?=display('company_name')?></th>
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
                              ?>
                         </tbody>
               </div>
          </div>
     </div>
</div>
<script type="text/javascript">
  function open_link(t)
  {
    window.open($(t).data('url'),'_blank');
  }
</script>