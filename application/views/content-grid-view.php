	<div class="row">
	<?php     
	foreach($courses as $key =>  $movie){ ?>				
		<div class="col-md-4 col-lg-4 pxpadding" style="height: 500px;">
			<div class="course-box-wrap" style="border:2px solid #2e6da4;border-radius: 20px 0px;">
					 <div class="course-image containerss"style="width:100%;height:250px;" >
						<img src="<?php echo base_url($movie->profile_image); ?>" alt="" class="img-fluid" style="width:60%;height:60px;padding-left:16%;padding-right:16%;margin-left: 40px">
									 <div class="text" style="color:#000000;height:100px;overflow-y: scroll;"><?php echo $movie->course_discription; ?></div>
					</div>
					 <div class="course-details" style="height:200px;margin-top: -85px;">
						 <h4 class="badge-success-lighten" id="h" style="color:#000000;font-size:16px;"><?php echo $movie->institute_name; ?></h5>
						<h5 class="badge-success-lighten" id="h" style="color:#F1CB0D;font-size:16px;">  
							  <?php if(!empty($movie->course_ielts)){ echo ($movie->course_ielts);}else{ echo '0';} ?>&nbsp;&nbsp; IELTS</h5> 												
						<h6 class="badge-success-lighten" id="h" style="color:#000000;font-size:14px;"><p>Course:-<?php echo $movie->course_name ?></p></h6>
					</div>
					<div style="padding-left:1%"><a href="<?php echo base_url('dashboard/course_details/'.$movie->institute_id.'/'.$movie->crs_id); ?>" class="btn btn-success"><span><i class="fa fa-info-circle" aria-hidden="true"></i></span></a>&nbsp;&nbsp;
					<a href="<?php $root=(isset($_SERVER["HTTPS"]) ? "https://" : "http://").$_SERVER["HTTP_HOST"]; echo $root.'/new_crm'; ?>" class="btn btn-danger"><span><i class="fa fa-heart-o" aria-hidden="true"></i></span></a>&nbsp;&nbsp;
					<a href="<?php $root=(isset($_SERVER["HTTPS"]) ? "https://" : "http://").$_SERVER["HTTP_HOST"]; echo $root.'/new_crm'; ?>" data-toggle="modal" data-target=".log-sign" class="btn btn-primary"><span><i class="fa fa-cart-plus" aria-hidden="true"></i></span> Apply</a>&nbsp;&nbsp;
					</div><br>
			</div>
		</div>						
	<?php $i++;} ?>
	<div class="load-more text-center" lastID="<?php echo $i; ?>">		
		<a href="javascript:void(0)" id="load-more" class="btn btn-primary">Load More ...</a>
		</div>
	</div>

	<script type="text/javascript">
		$("#load-more").on('click',function(){
        var lastID = $('.load-more').attr('lastID');        
        $.ajax({
            type:'POST',
            url:"<?=base_url().'programs/load_programs'?>",
            data:{'id':lastID},
            beforeSend:function(){
                $('.load-more').show();
            },
            success:function(html){
                $('.load-more').remove();
                $('#courses-area').append(html);
            }
        });
    });
	</script>

