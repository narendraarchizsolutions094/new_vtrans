"use strict";
$(document).ready(function () {
    $('form').attr('autocomplete','off');
    $('select[class="form-control"]').select2();
        
    $('[data-toggle="tooltip"]').tooltip();
    tinymce.init({
      selector: '.tinymce',      
      plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons ',
      imagetools_cors_hosts: ['picsum.photos'],
      menubar: 'file edit view insert format tools table help',
      toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',      
      toolbar_sticky: true,
      autosave_ask_before_unload: true,
      autosave_interval: "30s",
      autosave_prefix: "{path}{query}-{id}-",
      autosave_restore_when_empty: false,
      autosave_retention: "2m",
      image_advtab: true,
      importcss_append: true,  
      height: 600,
      image_caption: true,
      quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
      noneditable_noneditable_class: "mceNonEditable",
      toolbar_mode: 'sliding',
      contextmenu: "link image imagetools table",
     });
    $('.datatable1').DataTable({ 
        //responsive: true, 
        scrollX: true,
        // scrollY: 800,
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp", 
        "lengthMenu": [[30, 60, 90, -1], [30, 60, 90, "All"]], 
        buttons: [  
            {extend: 'copy', className: 'btn-sm'}, 
            {extend: 'csv', title: 'ExampleFile', className: 'btn-sm'}, 
            {extend: 'excel', title: 'ExampleFile', className: 'btn-sm', title: 'exportTitle'}, 
            {extend: 'pdf', title: 'ExampleFile', className: 'btn-sm'}, 
            {extend: 'print', className: 'btn-sm'} 
        ] 
    });
    //datatable
    $('.datatable2').DataTable({ 
        responsive : true, 
        paging : false,
        dom: "<'row'<'col-sm-4'B><'col-sm-4'l><'col-sm-4'f>>tp", 
        "lengthMenu": [[30, 60, 90, -1], [30, 60, 90, "All"]],         
        buttons: [  
            {extend: 'copy', className: 'btn-sm'}, 
            {extend: 'csv', title: 'ExampleFile', className: 'btn-sm'}, 
            {extend: 'excel', title: 'ExampleFile', className: 'btn-sm', title: 'exportTitle'}, 
            {extend: 'pdf', title: 'ExampleFile', className: 'btn-sm'}, 
            {extend: 'print', className: 'btn-sm'} 
        ] 
    });
	$('.chk-in-logs').DataTable({
		responsive : true, 
		"order": [],
	});
	$('.add-data-table').DataTable({ 
        responsive : true, 
		"pageLength": 50
	});
	
	
    // semantic button
    $('.ui.selection.dropdown').dropdown();
    $('.ui.menu .ui.dropdown').dropdown({
        on: 'hover'
    });
    //counter
    /*$('.count-number').counterUp({
        delay: 10,
        time: 3000
    });*/
    //Sparklines Charts
    $('.sparkline1').sparkline([4, 6, 7, 7, 4, 3, 2, 4, 6, 7, 4, 6, 7, 7, 4, 3, 2, 4, 6, 7, 7, 4, 3, 1, 5, 7, 6, 6, 5, 5, 4, 4, 3, 3, 4, 4, 5, 5, 6, 6, 7, 7], {
        type: 'bar',
        barColor: '#37a000',
        height: '35',
        barWidth: '3',
        barSpacing: 2
    });

    $(".sparkline2").sparkline([-8, 2, 4, 3, 5, 4, 3, 5, 5, 6, 3, 9, 7, 3, 5, 6, 9, 5, 6, 7, 2, 3, 9, 6, 6, 7, 8, 10, 15, 16, 17, 15], {
        type: 'line',
        height: '35',
        width: '100%',
        lineColor: '#37a000',
        fillColor: '#fff'
    });
    $(".sparkline3").sparkline([2, 5, 3, 7, 5, 10, 3, 6, 5, 7], {
        type: 'line',
        height: '35',
        width: '100%',
        lineColor: '#37a000',
        fillColor: '#fff'
    });
    $(".sparkline4").sparkline([10, 34, 13, 33, 35, 24, 32, 24, 52, 35], {
        type: 'line',
        height: '35',
        width: '100%',
        lineColor: '#37a000',
        fillColor: 'rgba(55, 160, 0, 0.7)'
    }); 
    //preloader
    $(window).load(function() {
        $(".se-pre-con").fadeOut("slow");;
    });
    // fixed table head
    $("#fixTable").tableHeadFixer();
});


//print a div
function printContent(el){
    var restorepage  = $('body').html();
    var printcontent = $('#' + el).clone();
    $('body').empty().html(printcontent);
    window.print();
    $('body').html(restorepage);
    location.reload();
}

$(document).on("click", ".nxt-tab-btn", function(e){
		
	e.preventDefault();
	
	var fields = $(this).closest(".tab-pane").find(".required");
	var iserr  = false;
	var msg    = "";
	fields.each(function(){
		
		if($(this).val() == ""){
			
			msg   = $(this).closest(".form-group").find("label").text();	
			
			alert(msg +"is required");
			$(this).focus();
			iserr = true;
			return false;
		}	
		
	});	
	
	if(iserr == false){
			$($(this).attr("href")).click();	
	}

	
});

/* functions for cookies start */
    function setCookie(cname, cvalue, exdays) {
      var d = new Date();
      d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
      var expires = "expires="+d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
      var name = cname + "=";
      var ca = document.cookie.split(';');
      for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
          c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
        }
      }
      return "";
    }
/* functions for cookies end */

/**/

$("input[name='product_filter[]']").on('change',function(){        
    var arr = Array();      
    $("input[name='product_filter[]']:checked").each(function(){
      arr.push($(this).val());
    });            
    setCookie('selected_process',arr,365);          
    var base_url = $('body').attr('data-baseUrl');
    var url = base_url+"enq/set_process_session";       
    $.ajax({
        type: "POST",
        url: url,
        data: {
            process:arr
        },
        success: function(data){       
           var enq_url = base_url+'enquiry/create';
           var curr_url = window.location.href;           
            // if(curr_url == enq_url){               
            // }
            window.location.reload();
        }
    });
});


/* crm setting */

$(document).ready(function()
{
    $("[rel='tooltip']").tooltip();
});

/* end crm setting */

