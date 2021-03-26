<div class="row">
    <div class="col-sm-12" id="PrintMe">
        <div  class="panel panel-default thumbnail"> 
            <!-- <div class="panel-heading no-print">
                 <div class="btn-group">
                </div>
            </div> -->
            <div class="panel-body">  
                <div class="row">
					<div class="col-md-12">					
						<a href="javascript:void(0)" data-toggle="modal" data-target="#create_tag" class="btn btn-primary">+ Create Tag</a>
					</div>
					<div class="col-sm-12" align="center">

					
                     <table class="table table-striped table-bordered add-data-table" id="filtered_Data1" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
										<th>Sr no</th>
										<th>Title</th>										
										<th>Created At</th>										
                                        <th>Created By</th>
                                        <th>Action</th>
                                      </tr>
                                    </thead>
                                    <tbody>
								<?php   

										$key = 0;

										if(!empty($tags)){
											foreach($tags as $key => $rslt){                                             
											?>
										<tr>	
											<td><?php echo $key + 1; ?></td>
											<td><a style="background-color: <?=$rslt['color']?>;font-size: 15px;padding: 5px;" href='javascript:void(0)' ><?php echo $rslt['title']; ?></a></td>											
											<td><?php echo $rslt['created_date']; ?></td>
											<td><?php echo $rslt['created_by_name']; ?></td>
											<td><a href="<?=base_url().'lead/delete_tag/'.$rslt['id']?>" onclick="return confirm('Are You sure?');" class='btn btn-danger btn-xs'><i class="fa fa-trash"></i></a></td>
										</tr>		
								<?php		}																				
										}else{
											
										?><tr><td colspan = "9">NO RECORD FOUND</td></tr><?php	
										} ?>
                                     
                                    </tbody>
                                </table>

                    </div> 



                    <div class="col-sm-8"> 


                    </div>

                </div>  



            </div> 



            <div class="panel-footer">

                <div class="text-center">

                </div>

            </div>

        </div>

    </div>

</div>
<div id="create_tag" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">    
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create Tag</h4>
      </div>
      <div class="modal-body">
      	<form action="<?=base_url().'lead/tags'?>" method='post'>	      		
	      	<div class="row">
	      		<div class="col-md-6">      			
		      		<label>Title</label>
		      		<input type="text" class="form-control" name="title">
	      		</div>
	      		<div class="col-md-2">      			
		      		<label>Color</label>
		      		<input type="color" class="form-control" name="color">
	      		</div>
	      	</div>
	      	<div class="row">	      		
		      	<br>
		      	<br>
	      		<div class="col-md-6">
	      			<input type="submit" name="Save" value="Save" class="btn-primary btn">	      			
	      		</div>
	      	</div>
	      	
      	</form>
      </div>      
    </div>
  </div>
</div>

