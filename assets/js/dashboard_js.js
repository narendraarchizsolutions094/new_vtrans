$(document).ready(function(){
 var BASEURL = $('body').attr('data-baseUrl');
   $("#fcountry").change(function(){                    
        var country = $(this).val();
        var html = '';        
        $.ajax({
            url : BASEURL+'Location/find_region',
            type:'POST',
            data:{country:country},            
            success:function(data){                
               var obj = JSON.parse(data);               
                html +='<option value="" style="display:none">---Select Region---</option>';
                for(var i=0; i < (obj.length); i++){                    
                    html +='<option value="'+obj[i].region_id+'">'+obj[i].region_name+'</option>';
                }
                $("#fregion").html(html);
            }
        });        
    });
    
    //Get region...
     $("#fregion").change(function(){                    
        var region_id = $(this).val();        
        var html1 ='';        
        $.ajax({            
            url : BASEURL+'Location/find_territory',
            type: 'POST',
            data:{region_id:region_id},
            success:function(data){                
                var obj = JSON.parse(data);                
                if(obj.lenght==0){                    
                    console.log('NOT data found');                    
                }else{                    
                    html1 +='<option value="" style="display:none">---Select Territory---</option>';                
                    for(var i=0; i < (obj.length); i++){                        
                        html1 +='<option value="'+obj[i].territory_id+'">'+obj[i].territory_name+'</option>';
                    }                    
                    $("#fterritory").html(html1);                    
                }                
            }            
        });        
    });    
     //Find state base on territory
    $("#fterritory").change(function(){        
        var territory_id = $(this).val();
        var html='';        
        $.ajax({            
            url : BASEURL+'Location/find_state',
            type: 'POST',
            data: {territory_id:territory_id},
            success:function(data){                
                var obj = JSON.parse(data);                 
                html +='<option value="" style="display:none">---Select State---</option>';                
                 for(var i=0; i < (obj.length); i++){                    
                     html +='<option value="'+obj[i].id+'">'+obj[i].state+'</option>';
                }                
                $('#fstate').html(html);
            }            
        });        
    });    
    //Find city based on state
    $("#fstate").change(function(){        
        var state_id = $(this).val();
    $.ajax({
            type: 'POST',
            url: BASEURL+'location/get_city_byid',
            data: {state_id:state_id},
            })
            .done(function(data){
            if(data!=''){
              document.getElementById('fcity').innerHTML=data;
            }else{
              document.getElementById('fcity').innerHTML='';   
            }
            })
            .fail(function() {            
            });            
    });
    
    //Ajax call for submit form data     
    $("#filter-form").submit(function(e){        
         e.preventDefault();         
         var html='';         
        $.ajax({            
            url : BASEURL+'Report/filter_enquiry_data',
            type:'POST',
            data:$(this).serialize(),
            success:function(data){                
                var obj = JSON.parse(data);                
                html +='<button class="btn btn-success downloadExcel" onclick="downloadExcel()"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button><br>';
                html +='<table class="table table-bordered"><thead><tr bgcolor="#37a000" style="color:#fff"><th>Sno.</th><th nowrap>Employee Name</th><th>Email</th><th>Phone</th><th>Country</th><th>Region</th><th>Territory</th><th>State</th><th>City</th><th>Pincode</th><th>Address</th><th>Organisation</th><th>Source</th><th nowrap>Opportunity Size</th><th>Status</th><th>Date</th></tr></thead>';
                var j=1;                
                if(obj==''){                    
                    html +='<tr><td colspan="18" align="center" style="color:red">No records found...</td></tr>';                    
                }else{                    
                    for(var i=0; i <(obj.length); i++){                        
                        var status;                        
                        if(obj[i].status==1){                            
                            status = 'Active';                            
                        }else if(obj[i].status==2){                            
                            status = 'Lead';                            
                        }else if(obj[i].drop_status==0){                            
                            status = 'Active';                            
                        }else if(obj[i].drop_status!=0){                            
                            status = 'Dropped'
                        }                        
                        html +='<tr><td>'+(j++)+'</td><td>'+(obj[i].s_display_name+" "+obj[i].last_name)+'</td><td>'+(obj[i].email)+'</td><td>'+(obj[i].phone)+'</td><td>'+(obj[i].country_name)+'</td><td>'+(obj[i].region_name)+'</td><td>'+(obj[i].territory_name)+'</td><td>'+(obj[i].state)+'</td><td>'+(obj[i].city)+'</td><td>'+(obj[i].pin_code)+'</td><td>'+(obj[i].address)+'</td><td>'+(obj[i].org_name)+'</td><td>'+(obj[i].lead_name)+'</td><td>'+(obj[i].op_size)+'</td><td>'+(status)+'</td><td>'+(obj[i].created_date)+'</td></tr>';                    
                    }                
                }
                html +='</table>';                
                $("#resultDiv").show();
                $("#showResult").html(html);
            }
        }); 
    });
    
     $('#lead-filter-form').submit(function(e){            
            e.preventDefault();            
         var html='';         
        $.ajax({            
            url : BASEURL+'Report/filter_leads_data',
            type:'POST',
            data:$(this).serialize(),
            success:function(data){                
                var obj = JSON.parse(data);                
                html +='<button class="btn btn-success" onclick="donloadleadsheet()"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>';
                html +='<table class="table table-bordered table-responsive"><thead><tr bgcolor="#37a000" style="color:#fff"><th>Sno.</th><th nowrap>Name</th><th>Email</th><th>Phone</th><th>Country</th><th>Region</th><th>Territory</th><th>State</th><th>City</th><th>Pincode</th><th>Address</th><th nowrap>Orgnisation Name</th><th>Source</th><th nowrap>Lead Score</th><th nowrap>Lead Stage</th><th>Date</th></tr></thead>';
                var j=1;
                if(obj==''){                    
                    html +='<tr><td colspan="16" align="center" style="color:red">No records found...</td></tr>';                    
                }else{                    
                    for(var i=0; i <(obj.length); i++){                        
                        html +='<tr><td>'+(j++)+'</td><td>'+(obj[i].ld_name)+'</td><td>'+(obj[i].ld_email)+'</td><td>'+(obj[i].ld_mobile)+'</td><td>'+(obj[i].country_name)+'</td><td>'+(obj[i].region_name)+'</td><td>'+(obj[i].territory_name)+'</td><td>'+(obj[i].state)+'</td><td>'+(obj[i].city)+'</td><td>'+(obj[i].pin_code)+'</td><td>'+(obj[i].address)+'</td><td>'+(obj[i].org_name)+'</td><td>'+(obj[i].lead_name)+'</td><td>'+(obj[i].lead_score)+'</td><td>'+(obj[i].lead_stage_name)+'</td><td>'+(obj[i].ld_created)+'</td></tr>';                    
                    }
                }
                html +='</table>';
                $("#showResult").html(html);
            }
        });
    });
    
    $('#client-filter-form').submit(function(e){            
            e.preventDefault();            
         var html='';         
        $.ajax({            
            url : BASEURL+'Report/filter_clients',
            type:'POST',
            data:$(this).serialize(),
            success:function(data){                
                var obj = JSON.parse(data);                
                html +='<button class="btn btn-success" onclick="donloadclientsheet()"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>';
                html +='<table class="table table-bordered table-responsive"><thead><tr bgcolor="#37a000" style="color:#fff"><th>Sno.</th><th nowrap>Client Name</th><th>Client Id</th><th>Email</th><th>Phone</th><th>Country</th><th>Region</th><th>Territory</th><th>State</th><th>City</th><th>Pincode</th><th>Address</th><th nowrap>Orgnisation Name</th><th>Date</th></tr></thead>';
               var j=1;
                if(obj==''){                    
                    html +='<tr><td colspan="14" align="center" style="color:red">No records found...</td></tr>';
                 }else{                    
                    for(var i=0; i <(obj.length); i++){                        
                        html +='<tr><td>'+(j++)+'</td><td>'+(obj[i].cl_name)+'</td><td>'+(obj[i].customer_code)+'</td><td>'+(obj[i].cl_email)+'</td><td>'+(obj[i].cl_mobile)+'</td><td>'+(obj[i].country_name)+'</td><td>'+(obj[i].region_name)+'</td><td>'+(obj[i].territory_name)+'</td><td>'+(obj[i].state)+'</td><td>'+(obj[i].city)+'</td><td>'+(obj[i].pin_code)+'</td><td>'+(obj[i].address)+'</td></td><td>'+(obj[i].org_name)+'</td><td>'+(obj[i].created_date)+'</td></tr>';
                    }                
                }
                html +='</table>';
                $("#showResult").html(html);
            }
        });
    });
    
    //filter Installation report...
    $('#InstallationReport').submit(function(e){
        e.preventDefault();            
         var html='';         
        $.ajax({            
            url : BASEURL+'Report/filter_installation_data',
            type:'POST',
            data:$(this).serialize(),
            success:function(data){                
                var obj = JSON.parse(data);                
                html +='<button class="btn btn-success" onclick="donloadinstallationsheet()"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>';
                html +='<table class="table table-bordered table-responsive"><thead><tr bgcolor="#37a000" style="color:#fff"><th>Sno.</th><th nowrap>Client Name</th><th>Client Id</th><th>Email</th><th>Phone</th><th>Country</th><th>Region</th><th>Territory</th><th>State</th><th>City</th><th>Pincode</th><th>Address</th><th nowrap>Orgnisation Name</th><th>Date</th></tr></thead>';
              
                var j=1;
                if(obj==''){                    
                    html +='<tr><td colspan="14" align="center" style="color:red">No records found...</td></tr>';
                    
                }else{                    
                    for(var i=0; i <(obj.length); i++){                        
                        html +='<tr><td>'+(j++)+'</td><td>'+(obj[i].cl_name)+'</td><td>'+(obj[i].customer_code)+'</td><td>'+(obj[i].cl_email)+'</td><td>'+(obj[i].cl_mobile)+'</td><td>'+(obj[i].country_name)+'</td><td>'+(obj[i].region_name)+'</td><td>'+(obj[i].territory_name)+'</td><td>'+(obj[i].state)+'</td><td>'+(obj[i].city)+'</td><td>'+(obj[i].pin_code)+'</td><td>'+(obj[i].address)+'</td></td><td>'+(obj[i].org_name)+'</td><td>'+(obj[i].created_date)+'</td></tr>';
                    }
                }
                html +='</table>';
                $("#showResult").html(html);
            }
        });
        
        
    });
});

//Download excel of Enquiry report...
function downloadExcel(){
    document.getElementsByClassName("submit-report-form")[0].submit();
}

//Download excel of Leads report...
function donloadleadsheet(){
    document.getElementsByClassName("lead-form")[0].submit();
}

//Download excel of Client report...
function donloadclientsheet(){
    document.getElementsByClassName("client-form")[0].submit();
}

//Download excel of installation...
function donloadinstallationsheet(){
 document.getElementsByClassName("installation-form")[0].submit();
}


/*-------------------------------------------------------------------------------JS FOR DASHBOARD-----------------------------------------------------------------------------*/

$(function(){    
    var BASEURL = $('body').attr('data-baseUrl');
    var country = $("#selectedcountry").attr('data-country');   
    $('.get-country').click(function(){        
        var country = $(this).attr('data-country');        
        $.ajax({            
            url : BASEURL+'dashboard/selected_country',
            type: 'POST',
            data: {country:country},
            success:function(data){                
                var obj = JSON.parse(data);                
                var dregion = '';
                dregion +='<option value="" style="display:none">---Select Region---</option>';
                for(var i=0; i < (obj['region'].length); i++){
                    
                    dregion += '<option value="'+(obj['region'][i].region_id)+'">'+(obj['region'][i].region_name)+'</option>';
                }
                $("#dregion").html(dregion);
                
                var dstate = '';
                dstate +='<option value="" style="display:none">---Select State---</option>';
                for(var i=0; i < (obj['state'].length); i++){
                    
                    dstate += '<option value="'+(obj['state'][i].id)+'">'+(obj['state'][i].state)+'</option>';
                }
                $("#dstate").html(dstate);
                
                var dteritory = '';
                dteritory +='<option value="" style="display:none">---Select Teritory---</option>';
                for(var i=0; i < (obj['territory'].length); i++){
                    
                    dteritory += '<option value="'+(obj['territory'][i].territory_id)+'">'+(obj['territory'][i].territory_name	)+'</option>';
                }
                $("#dteritory").html(dteritory);
                
                var dcity = '';
                dcity +='<option value="" style="display:none">---Select City---</option>';
                for(var i=0; i < (obj['city'].length); i++){
                    
                    dcity += '<option value="'+(obj['city'][i].id)+'">'+(obj['city'][i].city)+'</option>';
                }
                $("#dcity").html(dcity);
            }                       
        });
            });
    });

/* ------------------------------------------- Lead Status data on dashboard ------------------------------------------*/
    
    $(function(){ 
        /*var BASEURL = $('body').attr('data-baseUrl');
        $.ajax({
            url : BASEURL+'Report/lead_opportunity',
            type: 'POST',
            success:function(data){            
                var obj = JSON.parse(data);
                if(obj['hot']==null){
                    var hot = 0;                    
                }else{
                    var hot = obj['hot'].total;
                }
                
                 if(obj['warm']==null){                    
                    var warm = 0;                    
                }else{                    
                     var warm = obj['warm'].total;
                }
                
                //Shot data...
                $(".hot-total").html(hot);
                $(".warm-total").html(warm);
                $(".cold-total").html(obj['cold'].total);
                $(".led-revenue").html(obj['revenue'].total);
                $('.all-lead').html(obj['total_lead'].total);
            }            
        });
        */        
        //Clients Opportunity
        /*
        $.ajax({            
             url : BASEURL+'Report/client_opportunities',
             type: 'POST',
             success:function(data){
                 var obj = JSON.parse(data);
                 var a=parseInt(obj['total_tycon'].total);
                 var b=parseInt(obj['total_magnet'].total);
                 var c=parseInt(obj['total_ranger'].total);
                 var d=parseInt(obj['total_tyconn2'].total);
                 var e=parseInt(obj['total_magnet1'].total);
                 var f=parseInt(obj['total_ranger3'].total);
                 $(".total-client").html(obj['clients'].total); 
                 $('.total-installation').html(obj['installation'].total);
                 $('.inst-penddings').html(obj['pendding'].total);
                 $('.r-total').html(obj['total_revenue'].total);
                 $('.total-rev').html(obj['inst_rev'].total);
                 $('.pndding').html(obj['inst_pen'].total);
                 $('.actvie-ticket').html(obj['actvieticket'].total);
                 $('.ticket-value').html(obj['actvieticket_value'].total);
                 $('.ticket-value').html(obj['actvieticket_value'].total);                 
                $('.total-tycon').html(obj['total_tycon'].total);
                $('.total-magnet').html(obj['total_magnet'].total);
                $('.total-ranger').html(obj['total_ranger'].total);
                $('.revinue-tycon').html(obj['total_tyconn2'].total);
                $('.revinue-magnet').html(obj['total_magnet1'].total);
                $('.revinue-ranger').html(obj['total_ranger3'].total);
                $('.total-chanel').html(parseInt(a+b+c));
                $('.total-partner').html(parseInt(d+e+f));
             }
        });
        */
    });
/*------------------------------------------------------------------------------------------------------------*/
$(function(){    
    var BASEURL = $('body').attr('data-baseUrl');
    $("#dregion").change(function(){        
        var region_id = $(this).val();        
        $.ajax({            
            url : BASEURL+'Location/select_state_by_region',
            type: 'POST',
            data: {region_id:region_id},
            success:function(data){                
                var obj = JSON.parse(data);                
                var html='';                
                html +='<option value="" style="display:none;">---Select State---</option>';                
                for(var i=0; i <(obj.length); i++){                    
                    html +='<option value="'+(obj[i].id)+'">'+(obj[i].state)+'</option>';                
                }                
                $("#dstate").html(html);
            }
        });        
    });
    
    //Get Territory based on state..
    $("#dstate").change(function(){        
        var state_id = $(this).val();        
        var html='';        
        $.ajax({            
            url : BASEURL+'Location/select_territory_by_state',
            type: 'POST',
            data: {state_id:state_id},
            success:function(data){                
                var obj = JSON.parse(data);                
                html +='<option value="" style="display:none;">---Select Territory---</option>';                
                for(var i=0; i<(obj.length); i++){                    
                    html +='<option value="'+(obj[i].territory_id)+'">'+(obj[i].territory_name)+'</option>';
                }                
                $("#dteritory").html(html);
            }
        });       
    });    
    
    $("#dteritory").change(function(){        
        var territory_id = $(this).val();         
        $.ajax({            
            url : BASEURL+'Location/select_city',
            type: 'POST',
            data:{territory_id:territory_id},
            success:function(data){                
                var obj = JSON.parse(data);                
                var html='';                
                html +='<option value="" style="display:none">---Select City---</option>';                
                for(var i=0; i<(obj.length); i++){                    
                    html += '<option value="'+(obj[i].id)+'">'+(obj[i].city)+'</option>';
                }                
                $("#dcity").html(html);                
            }            
        });        
    });    
});