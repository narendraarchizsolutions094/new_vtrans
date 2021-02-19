<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<style>
  label {
    font-weight: bold;
    font-size: 13px;
  }
</style>

<div class="col-md-1"></div>
<div class="col-sm-10">
  <div class="panel panel-default thumbnail">
    <div class="panel-heading no-print">
      <div class="btn-group">
        <a class="btn btn-primary" href="<?php echo base_url("enquiry") ?>"> <i class="fa fa-list"></i>&nbsp;<?php echo display("enquiry"); ?></a>
      </div>
    </div>
    <div class="panel-body panel-form">
      <div class="row">
        <?php
        if (!$invalid_process) { ?>
          <form method="post" action="<?= base_url() ?>enquiry/create" id="enquiry_form" autocomplete="off"> 
          <input name="red" value="<?php if(isset($_GET['red'])){echo $_GET['red']; } ?>" hidden>
            <?php $process_id = $this->session->process[0]; ?>
            <input type="hidden" name="product_id" value="<?= $process_id ?>">
            <input type="hidden" name="status" value="<?php  if(!empty($this->input->get('status'))){   $status=$this->input->get('status');  }else{  $status=1; }
           echo $status;
               ?>">
            <div id="process_basic_fields" class="row">
            </div>
            <div class="row">
              <button class="btn btn-primary" type="submit">Save</button>
              <input class="btn btn-success" type="button" id="saveandnew" value="Save And Create New" />
            </div>
          </form>
        <?php

        } else {

        ?>
          <div>
            <div class="alert alert-danger">
              <strong>Please Select one process in which you want to create enquiry</strong>
            </div>
          </div>
        <?php
        }
        ?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $('#saveandnew').click(function() {
    $.ajax({
      url: "<?= base_url() ?>enquiry/create",
      type: 'post',
      dataType: 'json',
      data: $('form#enquiry_form').serialize(),
      success: function(data) {
        if (data.status == "success") {
          //swal("success","Your Enquiry has been  Successfully created","success");
          Swal.fire({
            icon: 'success',
            title: 'Your Enquiry has been  Successfully created',
            showConfirmButton: true,
          });
          $('#enquiry_form').trigger("reset");
        } else {
          Swal.fire({
            icon: 'warning',
            title: data.error,
            showConfirmButton: true,
          });
        }
      }
    });
  });
</script>

<script type="text/javascript">
  function add_more_phone(add_more_phone) {
    var html = '<div class="form-group col-sm-4"><label>Other No </label><input class="form-control"  name="other_no[]" type="text" placeholder="Other Number"   ></div>';
    $('#' + add_more_phone).append(html);
  }
</script>
<script type="text/javascript">
  $(function() {
    var process_id = "<?= $process_id ?>";
    if (process_id) {
      get_basic_field();
    }    
  });

  function get_basic_field() {
    var process_id = "<?= $process_id ?>";
    var para = '';
    if ("<?= !empty($_GET['phone']) ? $_GET['phone'] : '' ?>" != "") {
      para = "?phone=<?= !empty($_GET['phone']) ? $_GET['phone'] : '' ?>";
    }
    var url = "<?= base_url() . 'form/form/get_basic_field_by_process' ?>" + para;
    $.ajax({
      type: "POST",
      url: url,
      data: {
        'process_id': process_id
      },
      success: function(data) {
        $("#process_basic_fields").html(data);
        $("#fcity").select2();
        $("#fstate").select2();

        $(function() {
   
          $("input[name=company]").autocomplete({
            source: function( request, response ) {
                 $.ajax({
                  url: "<?=base_url('enquiry/suggest_company')?>",
                  type: 'post',
                  dataType: "json",
                  data: {
                   search: request.term
                  },
                  success: function( data ) {
                   response(data);
                  }
                 });
              },
          });
        });

        get_custom_field();
      }
    });
  }

  function get_custom_field() {
    var process_id = "<?= $process_id ?>";
    var url = "<?= base_url() . 'form/form/get_custom_field_by_process' ?>";
    $.ajax({
      type: "POST",
      url: url,
      data: {
        'process_id': process_id,
        'field_for': 0,
        'primary_tab':<?=$primary_tab?>,
      },
      success: function(data) {
        $("#process_basic_fields").append(data);
        if(typeof(hide_all_dependent_field)=== 'function')
          hide_all_dependent_field();

        if(typeof(relocate_mobile)=== 'function')
          relocate_mobile();
        $('select').select2();
      }
    });
  }


<?php
if($this->session->companey_id!=65)
{
?>
  $(document).on("change", '#fcity', function() {
    var selDpto = $(this).val(); // <-- change this line
    $.ajax({
      url: "<?php echo base_url(); ?>lead/select_state_by_city",
      async: false,
      type: "POST",
      data: {
        city_id: selDpto
      },
      dataType: 'html',
      success: function(data) {
        var obj = JSON.parse(data);
        $('#fstate option[value="' + obj.state_id + '"]').attr("selected", "selected");
        $('#fstate').trigger('change');
      }
    })
  });
<?php
}
?>

  $(document).on("change", '#fstate', function() {

    var id = $(this).val(); // <-- change this line
    $.ajax({
      url: "<?php echo base_url(); ?>location/get_city_byid",
      async: false,
      type: "POST",
      data: {
        state_id: id
      },
      dataType: 'html',
      success: function(data) {
        $('#fcity').html(data);
      }
    })
  });

  function find_description(f = 0) {
    if (f == 0) {
      var l_stage = $("#lead_stage_change").val();
      $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>lead/select_des_by_stage',
        data: {
          lead_stage: l_stage
        },

        success: function(data) {
          // alert(data);
          var html = '';
          var obj = JSON.parse(data);

          html += '<option value="" style="display:none">---Select---</option>';
          html += '<option value="new" style="">New</option>';
          html += '<option value="updt" style="">Update</option>';
          for (var i = 0; i < (obj.length); i++) {
            html += '<option value="' + (obj[i].id) + '">' + (obj[i].description) + '</option>';
          }
          $("#lead_description").html(html);
        }
      });
    }
  }

</script>
<?php
if ($this->session->companey_id == 79) {
?>
  <script>
    $('#vertex-course').on('change', function() {
      subcourse();
    });

    function subcourse() {
      var options = '';
      course = $("#vertex-course").val();
      if (course == 'SCIENCE & DEVELOPMENT') {
        options = `<option>WEB DEVELOPMENT 
                        <option>DATA SCIENCE</option> 
                        <option>MOBILE APPS</option> 
                        <option>CODINGS</option> 
                        <option>GAMING</option> 
                        <option>DATABASES</option>
                        <option>SOFTWARE TESTING</option> 
                        <option>ECOMMERCE</option> 
                        <option>TOOLS FOR WEBSITE</option> 
                        <option>ALGORITHMS</option> 
                        <option>SOFTWARE DEVELOPMENT</option> 
                        <option>SECURITY AND NETWORKS</option>`;
      } else if (course == 'DESIGN') {
        options = `<option>WEB DESIGN</option> 
                        <option>GRAPHIC DESIGN</option> 
                        <option>GAME DESIGN</option> 
                        <option>3D AND ANIMATION</option>
                        <option>FASHION DESIGNING</option> 
                        <option>ARCHITECHTURE</option> 
                        <option>INTERIOR DESIGN</option>`;

      } else if (course == 'BUSINESS') {
        options = `<option>FINANCE</option> 
                        <option>MANAGEMENT</option> 
                        <option>SALES</option>
                        <option>HUMAN RESOUCE</option> 
                        <option>COMMUNICATION</option> 
                        <option>PROJECT MANAGEMENT</option> 
                        <option>DIGITAL MARKETING</option> 
                        <option>MARKETING</option> 
                        <option>MEDIA</option> 
                        <option>ENTREPRENUERSHIP</option> 
                        <option>BUSINESS STARTERGY</option>  
                        <option>OPERATIONS</option> 
                        <option>DATA AND ANALYTICS</option> 
                        <option>BUSINESS LAW</option>
                        <option>REAL ESTATE</option> 
                        <option>DATA MANAGEMENT</option>`;
      } else if (course == 'LIFESTYLE') {
        options = `<option>ARTS</option> 
                        <option>PHOTOGRAPHY</option>
                        <option>GAMING</option> 
                        <option>MUSIC</option> 
                        <option>COOKING</option>`;
      } else if (course == 'ACADEMICS') {
        options = `<option>ENGINEERING</option> 
                        <option>HUMANITIES</option> 
                        <option>SOCIAL STUDIES</option> 
                        <option>SCIENCE</option> 
                        <option>MATHEMATICS</option> 
                        <option>LANGUAGES</option>`;
      } else if (course == 'PROFESSIONAL') {
        options = `<option>LEADERSHIP</option> 
                        <option>STRESS MANAGEMENT</option> 
                        <option>MOTIVATION</option> 
                        <option>CAREER DEVELOPMENT</option> 
                        <option>SOFT SKILLS</option>`;

      } else if (course == 'TECHNOLOGY & DATA') {
        options = `<option>CLOUD COMPUTING</option> 
                        <option>NETWORKING</option> 
                        <option>DATA ANALYTICS</option>
                        <option>PROBABILITY AND STATISTICS</option>
                        <option>MACHINES LEARNING</option>`;

      } else if (course == 'LANGUAGES') {
        options = `<option>ENGLISH</option> 
                        <option>FRENCH</option> 
                        <option>SPANISH</option> 
                        <option>JAPANESE</option>
                        <option>GERMAN</option>
                        <option>CHINESE</option>`;

      } else if (course == 'Fitness') {
        options = `<option>Yoga</option>
                        <option>Zumba</option>
                        <option>Diet</option>`;
      } else if (course == 'Other') {

      }
      $("#vertex-sub-course").html(options);
    }
  </script>
<?php
}
if($this->session->process[0]=='200')
{
  //moble number relocate in V-trans process 200
?>

<script type="text/javascript">
function relocate_mobile()
{
  var enq_form = $("#process_basic_fields");
  var mobile_fld= $(enq_form).find('input[name=mobileno]').parents('div.form-group');
  var more_phone = $("#add_more_phone");
  $(mobile_fld).find('label').html('Parents Mobile No <font color="red">*</font>');
  var mobile_clone = $(mobile_fld).clone();
  var more_clone = more_phone.clone();
 $(enq_form).find('#parent-name').parents('div.form-group').after(mobile_clone);
 //$(enq_form).find('input[name=mobileno]').parents('div.form-group').after(mobile_clone);
 $(mobile_clone).after(more_clone);
 $(mobile_fld).remove();
 $(more_phone).remove();

}
</script>

<?php
}
?>
<script>
  $(document).ajaxComplete(function(){ 
    if("<?=$this->session->companey_id?>"==65){
      $(".designation").insertAfter(".enq-last-name");
      $(".enq-remark").insertAfter(".website");
    }
  });
</script>