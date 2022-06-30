
    <script src="<?php echo base_url('assets/js/jquery.min.js?v=1.0') ?>" type="text/javascript"></script>

<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

   <script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>" type="text/javascript"></script>
   <script src="<?=base_url()?>/assets/summernote/summernote-bs4.min.js"></script>
<link href="<?=base_url()?>/assets/summernote/summernote-bs4.css" rel="stylesheet" />
<style>
    .modal-body {
        position: relative;
        padding: unset!important;
    }
    </style>

<div id="sendsms" class="modal fade " role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modal-titlesms"></h4>
      </div>
      <div class="modal-body">
      <div>
      <form id="email-form" action="<?=base_url('dashboard/pdf_gen/').$info_id?>" method="post">
                <input type="hidden" name="submit" value="Email">
          <div class="form-group col-sm-12">
                <label>Template</label>
                <select class="form-control" name="templates" required id="templates"   onchange="getMessage()">
               
                </select>
                </div>
                <div class="form-group col-sm-12"> 
                  
                  <label><?php echo display('subject') ?></label>
                  <input type="text" name="email_subject" class="form-control" id="email_subject">

                <label><?php echo display('message') ?></label>
                <textarea class="form-control" name="message_name"  rows="10" id="template_message"></textarea>  
                </div>
      </div>
      
       <div class="col-md-12">
                       <input type="hidden"  id="mesge_type" name="mesge_type">                                         
                    <button class="btn btn-success"  type="submit">Send</button>            
                 

              </div>
        </form>
            </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script>
  function send_sms(){
    //email-form
    subject = $("#email_subject").val();
    content = $("#template_message").val();
    $("input[name='form_email_subject']").val(subject);
    $("input[name='form_email_content']").val(content);
    alert('this is test alert');
    $("#email-form").submit();
  }
  function getTemplates(SMS,type){
     if(type != 'Send Email'){
       $("#email_subject").hide();
      $("#email_subject").prev().hide();
      $("#template_message").summernote('code','');
      $("#template_message").summernote('destroy');
    }else{
      $("#msg_templates_message").html('');
      $("#template_message").summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false                 // set focus to editable area after initializing summernote
      });
      $("#email_subject").show();
      $("#email_subject").prev().show();
    }
  $.ajax({
  type: 'POST',
  url: '<?php echo base_url();?>message/get_templates/0/3/'+SMS,
  })
  .done(function(data){
      // alert(data);
       $('#modal-titlesms').html(type);
       $('#mesge_type').val(SMS);       
       $('#templates').html(data);
      //$("#email_subject").val(data.mail_subject);

  })
  .fail(function() {
      alert( "fail!" );
  });
  }


  function getMessage(){        
        var tmpl_id = document.getElementById('templates').value;        
        $.ajax({            
            url : '<?php echo base_url('enquiry/msg_templates') ?>',
            type: 'POST',
            data: {tmpl_id:tmpl_id},
            success:function(data){                
                var obj = JSON.parse(data);
                $('#templates option[value='+tmpl_id+']').attr("selected", "selected");
                 $("#template_message").summernote('destroy');                
                  if($("#email_subject").is(':visible')){
                    $("#template_message").summernote("code", obj.template_content);
                       $("#email_subject").val(obj.mail_subject);
                  }
                  else
                  {
                      $("#template_message").val(obj.template_content);
                   
                  }
            }
            
        });
      
  }   
  </script>