<?php
	include("security.php");
  if(isset($_POST['app_key']) && $_POST['app_key']!=""){
    $NICAppKey=$_POST['app_key'];
    $EinvPassword=$_POST['api_password'];
    $EinvUsername=$_POST['api_username'];
    $secCls = new security();
    $response = $secCls->EinvApiAuthenticate($EinvUsername,$EinvPassword, $NICAppKey);
    $_post['request_payload'] = $response;
  }
?>
<html>
 <header>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<!-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script> -->
<!------ Include the above in your HEAD tag ---------->
</header>
<body> 
<div class="container">
	  <form role="form" id="auth_form" method="post" action="">
      <h1>Generate Authentication Request Payload</h1>
      <div class="row">
        <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4">
          <h3>Input Parameter</h3>
            <label>NIC APP-Key</label>
            <input type="text" class="form-control" maxlength="32" name="app_key" id="app_key" placeholder="Enter App Key " required value="<?php if(isset($NICAppKey)){
              echo $NICAppKey;  }?>">
            Sample App-key: Xuulgw1cEpZo4riYInj7UQFfO5n2voYi
          </div>
        
        <div class="clearfix"></div>
        <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4">
            <label>NIC API Username</label>
            <input type="text" class="form-control" name="api_username" id="api_username" placeholder="Enter NIC API Username" required value="<?php if(isset($EinvUsername)){
              echo $EinvUsername;  }?>" >
        </div>
        <div class="clearfix"></div>
        <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4">
            <label >NIC API Password</label>
            <input type="text" class="form-control" name="api_password" id="api_password" placeholder="Enter NIC API Password" required value="<?php if(isset($EinvPassword)){
              echo $EinvPassword;  }?>" >
        </div>
       
        <div class="clearfix"></div>
        <div class="col-xs-10 col-sm-4 col-md-4 col-lg-4">
            <button type="submit" class="btn btn-default">Submit</button>
        </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
          <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4">
            <h3>Output</h3>
            <label>Encrypted Request Payload</label>
            <textarea id="request_payload" name="request_payload" rows="4" cols="50"><?php if(isset($_post['request_payload'])){
              echo $_post['request_payload'];
            }?></textarea>
                <button type="button" class="btn btn-default" onclick="copy()">Copy</button>
          </div>
    </form>
    <div class="clearfix"></div>

    <br /><br />
	</div>

</body>
</html>
<script>
function copy() {
  var copyText = document.getElementById("request_payload");
  copyText.select();
  document.execCommand("copy");
}
</script>