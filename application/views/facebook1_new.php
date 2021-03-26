<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '727296608087888',
      xfbml      : true,
      version    : 'v8.0'
    });
  };
  // 2671840339741760
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

  function subscribeApp(page_id, page_access_token) { 
    console.log('Subscribing page to app! ' + page_id);
       console.log('Subscribing page to app! ' + page_access_token);
    FB.api(
      '/' + page_id + '/subscribed_apps',
      'post',
      {access_token: page_access_token, subscribed_fields: ['leadgen']},
      function(response) {
       alert('success');
       swal({
  title: "success",
  text: "Successfully subscribed page",
  icon: "success",
});
        console.log('Successfully subscribed page', response);
      }
    );
  }
    
  // Only works after `FB.init` is called
  function myFacebookLogin() {
    FB.login(function(response){
      console.log('Successfully logged in', response);
      FB.api('/me/accounts', function(response) {
        console.log('Successfully retrieved pages', response);
        var pages = response.data;
        var ul = document.getElementById('list');
        for (var i = 0, len = pages.length; i < len; i++) {
          var page = pages[i];
          var li = document.createElement('li');
          var a = document.createElement('a');
          a.href = "#";
          a.onclick = subscribeApp.bind(this, page.id, page.access_token);
          a.innerHTML = page.name;
          li.appendChild(a);
          ul.appendChild(li);
          $.ajax({
      url : "<?=base_url('dashboard/fb_page_new')?>",
      type: "post",
      dataType : "json",
      data :{page_id:page.id,page_token:page.access_token},
      success : function(response)
      {
       alert('success');

       }});
        }
      });
    }, {scope: 'pages_show_list'});
  }
</script>
<?= $this->session->flashdata('form-data');?>
<br>
<div class="col-md-12 text-center"><br><br>
<button style="float: right; margin-bottom:20px;" onclick="myFacebookLogin()" class="btn btn-info">Get Your Leads</button>
</div>
<ul id="list"></ul>
<?php 
$comp_id=$this->session->companey_id;
$user_id=$this->session->user_id;
$pages=$this->db->where(array('comp_id'=>$comp_id,'user_id'=>$user_id))->get('fb_page')->result();


?>
<div class="container">
	<div class="row ">
    <table  class=" table table-striped table-bordered ">
        <thead>
            <th>S.no</th>
            <th>Page Id</th>
            <th>Timestamp</th>
            <th>Action</th>
        </thead>
        <tbody>
            <?php 
            $i=1;
            foreach ($pages as $key => $value) { ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $value->page_id ?></td>
                    <td><?= $value->timestamp ?></td>
                    <td><a class="btn-primary btn"  data-toggle="modal" data-target="#updatepage<?= $value->page_id ?>" >Edit</a>
                     <a href="<?= base_url('dashboard/renameFacebookToken/'.$value->page_id.'') ?>" class="btn-primary btn">Renew Token</a>
                   
<div id="updatepage<?= $value->page_id ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Set Your facebook data with Enquiry  fields</h4>
      </div>
      <div class="modal-body">
        <form action="<?= base_url('dashboard/update_fbpage') ?>" method="POST" >
        <input name="page_id" value="<?= $value->page_id ?>" hidden> 
          <div class="row">
            <div class="form-group col-sm-6">
            <label class="col-form-label">Select Process </label>
            <select class="form-control"  name="process_id">
            <?php 
            $process=$this->db->where(array('comp_id'=>$comp_id,'status'=>1))->get('tbl_product')->result();
            foreach ($process as $key => $pvalue) { ?>
                <option <?php if($value->process_id==$pvalue->sb_id){echo'selected';} ?> value="<?= $pvalue->sb_id ?>"> <?=$pvalue->product_name; ?> </option>
            <?php }
            ?>                                               
            </select>
            </div>
            <br>
          </div>
          
          <?php 
          $this->db->select('*,input_types.title as input_type_title,'); 		
          $this->db->where('tbl_input.page_id',0);  			
          $this->db->where('tbl_input.input_type',1);  			
          $this->db->where('tbl_input.company_id',$comp_id);  			
          $this->db->join('input_types','input_types.id=tbl_input.input_type');  			
          $data['form_fields']	= $this->db->get('tbl_input')->result_array();
            // print_r($data['form_fields']); 
            // exit();
            ?>
          
          <div class="row">
            <div class="form-group col-sm-6">  
            <label>Form Name</label>                  
             <select name="form_name" class="form-control" >
            <option value="0">Select</option>

             <?php   
            foreach ($data['form_fields'] as $key => $fvalue) {    ?>
            <option value="<?= $fvalue['input_id']; ?>" <?php if($value->form_name==$fvalue['input_id']){echo'selected';} ?>><?= $fvalue['input_label']; ?></option>
                    <?php } ?>
             </select>               
            </div>
            <div class="form-group col-sm-6">  
            <label>Compaign Name</label>                  
             <select name="compaign_name" class="form-control" >
            <option value="0">Select</option>

             <?php   
            foreach ($data['form_fields'] as $key => $cvvalue) {    ?>
            <option value="<?= $cvvalue['input_id']; ?>" <?php if($value->compaign_name==$cvvalue['input_id']){echo'selected';} ?>><?= $cvvalue['input_label']; ?></option>
                    <?php } ?>
             </select>               
            </div>
           
            <div class="form-group col-sm-6">  
            <label>Add set name</label>                  
             <select name="add_set_name" class="form-control" >
            <option value="0">Select</option>

             <?php   
            foreach ($data['form_fields'] as $key => $asvalue) {    ?>
            <option value="<?= $asvalue['input_id']; ?>" <?php if($value->add_set_name==$asvalue['input_id']){echo'selected';} ?>><?= $asvalue['input_label']; ?></option>
                    <?php } ?>
             </select>               
            </div>
            <div class="form-group col-sm-6">  
            <label>Add Name</label>                  
             <select name="add_name" class="form-control" >
            <option value="0">Select</option>

             <?php   
            foreach ($data['form_fields'] as $key => $avalue) {    ?>
            <option value="<?= $avalue['input_id']; ?>" <?php if($value->add_name==$avalue['input_id']){echo'selected';} ?>><?= $avalue['input_label']; ?></option>
                    <?php } ?>
             </select>               
            </div>
            <div class="form-group col-sm-6">  
            <label>Course Name</label>                  
             <select name="course_name" class="form-control" >
            <option value="0">Select</option>

             <?php   
            foreach ($data['form_fields'] as $key => $cvalue) {    ?>
            <option value="<?= $cvalue['input_id']; ?>" <?php if($value->course_name==$cvalue['input_id']){echo'selected';} ?>><?= $cvalue['input_label']; ?></option>
                    <?php } ?>
             </select>               
            </div>
            <div class="form-group col-sm-6">
            <label class="col-form-label">Response</label>
            <select name="response" class="form-control" >
            <option value="0">Select</option>

             <?php   
            foreach ($data['form_fields'] as $key => $fvalue) {    ?>
            <option value="<?= $fvalue['input_id']; ?>" <?php if($value->response==$fvalue['input_id']){echo'selected';} ?>><?= $fvalue['input_label']; ?></option>
                    <?php } ?>
             </select>  
            </div>
          </div>
          <div class="row">
          <div class="form-group col-sm-12">        
            <button class="btn btn-success" type="submit" >Submit</button>        
            </div>
      </div>
        </form>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

                </td>
                </tr>
             <?php } ?>
        </tbody>
    </table>
    </div>
</div>


 
