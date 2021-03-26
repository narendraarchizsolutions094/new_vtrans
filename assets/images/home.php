<link rel="stylesheet" href="<?php echo base_url()?>custom_dashboard/assets/css/dashforge.css">
<link rel="stylesheet" href="<?php echo base_url()?>custom_dashboard/assets/css/dashforge.dashboard.css">
<link href="<?php echo base_url()?>custom_dashboard/lib/morris.js/morris.css" rel="stylesheet">
 <style>        
        .wd-10{            
            width:58px !important;
        }        
        .rounded-circle {
            border-radius: 0px !important; 
        }        
        #chartdiv {
          width: 100%;
          height: 500px;
        }      
  </style>  
  </head>
  <body class="page-profile" style="background-color:#fff;">
     <div class="content">
      <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30"> 
          <div>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item"></li> <div class="dropdown">
                  <span><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><span id="selectdashboard" data-country="3">
                      <?php if(user_access('60')==true||user_access('70')==true||user_access('80')==true){?>Sales<?php }else if(user_access('100')==true ||user_access('101')==true||user_access('102')==true||user_access('103')==true||user_access('104')==true||user_access('105')==true||user_access('106')==true||user_access('110')==true||user_access('111')==true||user_access('112')==true||user_access('113')==true||user_access('114')==true||user_access('115')==true||user_access('116')==true){?>
                       Service
                       <?php }?></span>
                  <span class="caret" style="font-size:14px;"></span></a>
                  <ul class="dropdown-menu" id="selecteddashboard">
                      <?php if(user_access('60')==true||user_access('70')==true||user_access('80')==true){?>
                      
                   <li><a  href="javascript:void(0)" onclick="sales_dash()">Sales</a></li>
                    <?php }?>
                      <?php if(user_access('100')==true ||user_access('101')==true||user_access('102')==true||user_access('103')==true||user_access('104')==true||user_access('105')==true||user_access('106')==true||user_access('110')==true||user_access('111')==true||user_access('112')==true||user_access('113')==true||user_access('114')==true||user_access('115')==true||user_access('116')==true){?>
                       <li><a href="javascript:void(0)" onclick="changeMenu('dashboard','sales_dashboard')">Service</a></li>
                    <?php }?>
                  </ul>
                  </span>
                </div>
                </li>                
              </ol>
            </nav>
          </div>
          <div class="d-none d-md-block">
            <?php foreach($countries as $c){                
                $default_country =  lcfirst($c->country_name);                
                if($default_country=='india'){                    
                    $country = $c->id_c;
                }
            }
            ?>
            <div class="dropdown">                
              <span><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><span id="selectedcountry" data-country="<?= $country ?>">India</span>
              <span class="caret" style="font-size:14px;"></span></a>
              <ul class="dropdown-menu" id="droupdown-value">
                <?php foreach($countries as $row){?>
                <li data-country="<?= $row->id_c ?>" class="get-country"><a href="javascript:void(0)"><?= $row->country_name ?></a></li>
                <?php } ?>
              </ul>
              </span>
            </div>            
          </div>
        </div>   
        <?php 
        
         /* user roles
	    3 = Country Head
	    4 = Region Head
	    5 = Territory Head
	    6 = State Head
	    7 = City Head 
	    8 = User */
	    
	   $user_id   = $this->session->user_id;
	   $user_role = $this->session->user_role;
	   $region_id = $this->session->region_id;
	   $assign_country = $this->session->country_id;
	   $assign_region = $this->session->region_id;
	   $assign_territory = $this->session->territory_id;
	   $assign_state = $this->session->state_id;
	   $assign_city = $this->session->city_id;
	   
	   ?>
	   <?php if($user_role==1 || $user_role==2 || $user_role==3 || $user_role==4){?>
        <div class="row row-xs">           
            <div class="col-sm-6 col-lg-3">                
                <select name="region" id="dregion" class="form-control">
                    <option value="" style="display:none">---Select Region---</option>
                    <?php foreach($locations['region'] as $region){?>                        
                        <option value="<?= $region->region_id ?>"><?= $region->region_name ?></option>          
                    <?php } ?>
                </select>                
            </div>            
            <div class="col-sm-6 col-lg-3">                
                <select name="state" id="dstate" class="form-control">                    
                    <option value="" style="display:none">---Select State---</option>
                    <?php foreach($locations['state'] as $state){?>                        
                        <option value="<?= $state->id ?>"><?= $state->state ?></option>                        
                    <?php } ?>               
                </select>                
            </div>            
            <div class="col-sm-6 col-lg-3">                
            <select name="teritory" id="dteritory" class="form-control">
               <option value="" style="display:none">---Select Territory---</option>               
               <?php foreach($locations['territory'] as $territory){?>                        
                    <option value="<?= $territory->territory_id ?>"><?= $territory->territory_name ?></option>         
                <?php } ?>               
            </select>               
            </div>            
            <div class="col-sm-6 col-lg-3">                
            <select name="city" id="dcity" class="form-control">
               <option value="" style="display:none">---Select City---</option>
               <?php foreach($locations['city'] as $city){?>                        
                    <option value="<?= $city->id ?>"><?= $city->city ?></option>                        
                <?php } ?>               
            </select>                
            </div>          
        </div><br>
        <?php }else{?>            
            <div class="row row-xs">           
            <div class="col-sm-6 col-lg-3">                
                <select name="region" id="dregion" class="form-control">
                    <option value="" style="display:none">---Select Region---</option>
                    <?php foreach($locations['region'] as $region){                        
                        if($assign_region==$region->region_id){                            
                            $region_id = $region->region_id;                            
                            $region_name = $region->region_name;                   
                        }} ?>                        
                        <option value="<?=$region_id ?>" selected><?=$region_name ?></option>                     
                </select>                
            </div>            
            <div class="col-sm-6 col-lg-3">                
                <select name="state" id="dstate" class="form-control">                    
                    <option value="" style="display:none">---Select State---</option>
                    <?php foreach($locations['state'] as $state){                        
                         if($assign_state==$state->id){                             
                             $state_id = $state->id;                             
                             $state_name = $state->state;
                         }} ?>
                    <option value="<?= $state_id ?>" selected><?= $state_name ?></option>                     
                </select>                
            </div>            
            <div class="col-sm-6 col-lg-3">                
            <select name="teritory" id="dteritory" class="form-control">
               <option value="" style="display:none">---Select Territory---</option>
               <?php foreach($locations['territory'] as $territory){                        
                   if($territory->territory_id==$assign_territory){                       
                       $territory_id = $territory->territory_id;                       
                       $territory_name = $territory->territory_name;
                   }                       
               }?>                
                 <option value="<?php echo $territory_id ?>" selected><?= $territory_name ?></option>
               
            </select>
                
            </div>
            
            <div class="col-sm-6 col-lg-3">
                
            <select name="city" id="dcity" class="form-control">
               <option value="" style="display:none">---Select City---</option>
               <?php foreach($locations['city'] as $city){
                        
                             
                   if($city->id==$assign_city){
                       
                       $city_id = $city->id;
                       
                       $city_name = $city->city;
                       
                   }} ?>
                
                
                <option value="$city_id" selected><?= $city_name ?></option>
               
            </select>
                
            </div>
            
            
         </div><br>
            
        <?php } ?>
        
        <div id="content_tabs"></div>
        <div id="content_tabs1">
            
        <div class="row row-xs">
          <div class="col-sm-6 col-lg-3">
            <div class="card card-body" style="height:158px">
                
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8" style="font-size: 14px;color:red"><?= display('enquiry') ?><span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1" style="margin-left: 22%;font-size: 14px;">  
              <?php 
              
                    		$drop=0;$value=0;$enq_active=0;$enq_value=0;$total_active=0;$total_value=0;
                                 foreach ($all_enquery->result() as $enquiry){
                                 if($enquiry->drop_status>0){
                                     $drop++;$value=$enquiry->op_size+$value;
                                  }elseif($enquiry->status==1){
                                  $enq_active++;
                                 $enq_value=$enquiry->op_size+$enq_value;}
                                 $total_active++;
                                 $total_value=$enquiry->op_size+$total_value;
                                 
                 }
               
               
          ?><?php echo $total_active; ?>  </span><span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right" style="font-size: 14px;"><?php echo $total_value; ?></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Active <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1" style="margin-left: 34%;"><?php echo $enq_active; ?></span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right "><?php echo $enq_value; ?></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Dead <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1" style="margin-left: 38%;"><?php echo $drop; ?></span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right"><?php echo $value; ?></span></h6>
            
              
              <div class="d-flex d-lg-block d-xl-flex align-items-end">
                <!--<h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1"><?php echo (!empty($total_enquiry->total) ? $total_enquiry ->total : 0) ?></h3>-->
                <!--<p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium tx-success">1.2% <i class="icon ion-md-arrow-up"></i></span> than last week</p>-->
              </div>
              <div class="chart-three" style="margin-top: 21px;">
                  <div id="flotChart3" class="flot-chart ht-30"></div>
                </div><!-- chart-three -->
            </div>
          </div><!-- col -->
          <div class="col-sm-6 col-lg-3 mg-t-10 mg-sm-t-0">
            <div class="card card-body">
                
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8" style="font-size: 14px;color:#6600cc"><?= display('lead') ?><span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1" style="margin-left: 29%;font-size: 14px;">
               
                <?php 
              
                    		$dropld=0;$valueld=0;$enq_activeld=0;$enq_valueld=0;$total_activeld=0;$total_valueld=0;$worm_activeld=0;$wormtotal_valueld=0;
                                 foreach ($all_active->result() as $enquiry){
                                 if($enquiry->lead_score==1){
                                     $dropld++;$valueld=$enquiry->op_size+$valueld;
                                  }elseif($enquiry->lead_score==2){
                                  $enq_activeld++;
                                 $enq_valueld=$enquiry->op_size+$enq_valueld;}
                                 elseif($enquiry->lead_score==3){$total_activeld++;
                                 $total_valueld=$enquiry->op_size+$total_valueld;}
                                 $wormtotal_valueld++;
                                 $worm_activeld=$enquiry->op_size+$worm_activeld;
                                 
                 }
               
               
          ?>   
                  
             <?php echo $wormtotal_valueld; ?>     
              </span><span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right " style="font-size: 14px;"><?php echo $worm_activeld; ?></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Hot <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 " style="margin-left: 35%;"><?php echo $total_activeld; ?></span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right "><?php echo $valueld; ?></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">warm <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 " style="margin-left: 28%;"><?php echo $enq_activeld; ?></span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right"><?php echo $enq_valueld; ?></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">cold <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1" style="margin-left: 31%;"><?php echo $dropld; ?></span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right"><?php echo $total_valueld; ?></span></h6>
              
              <div class="d-flex d-lg-block d-xl-flex align-items-end">
                <!--<h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1"><?php echo (!empty($leads->total) ? $leads->total : 0) ?></h3>
                <p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium tx-danger">0.7% <i class="icon ion-md-arrow-down"></i></span> than last week</p>-->
              </div>
              <div class="chart-three">
                  <div id="flotChart4" class="flot-chart ht-30"></div>
                </div><!-- chart-three -->
            </div>
          </div><!-- col -->
          <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
            <div class="card card-body">
              
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8" style="font-size: 14px;color:#0066ff"><?= display('doctor') ?><span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 total-client" style="margin-left: 31%;font-size: 14px;"></span><span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right r-total" style="font-size: 14px;"></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Inst Comp <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 total-installation" style="margin-left: 24%;"></span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right total-rev"></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Inst pending <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 inst-penddings" style="margin-left: 14%;"></span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right pndding"></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Active Ticket <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 actvie-ticket" style="margin-left: 15%;"></span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right ticket-value"></span></h6>
              
              <!--<h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8"><?= display('doctor') ?></h6>-->
              <div class="d-flex d-lg-block d-xl-flex align-items-end">
                <!--<h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1"><?php echo (!empty($clients->total) ? $clients->total : 0) ?></h3>-->
                <!--<p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium tx-danger">0.3% <i class="icon ion-md-arrow-down"></i></span> than last week</p>-->
              </div>
              <div class="chart-three">
                  <div id="flotChart5" class="flot-chart ht-30"></div>
                </div><!-- chart-three -->
            </div>
          </div><!-- col -->
          <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
            <div class="card card-body">
                
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8" style="font-size: 14px;color:#009900">Channel Partner<span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 total-chanel" style="margin-left: 11%;font-size: 14px;"></span><span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right total-partner" style="font-size: 14px;"><b></b></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Tycoon <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 total-tycon" style="margin-left: 29%;"></span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right revinue-tycon"></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Magnate <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 total-magnet" style="margin-left:25%;"></span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right revinue-magnet"></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Ranger <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 total-ranger" style="margin-left:30%;"></span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right revinue-ranger"></span></h6>
              
              <div class="d-flex d-lg-block d-xl-flex align-items-end">
                <!--<h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1"><?php echo (!empty($all_invoices->total) ? $all_invoices->total : 0) ?></h3>-->
                <!--<p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium tx-success">2.1% <i class="icon ion-md-arrow-up"></i></span> than last week</p>-->
              </div>
              <div class="chart-three">
                  <div id="flotChart6" class="flot-chart ht-30"></div>
                </div><!-- chart-three -->
            </div>
            
          </div><!-- col -->
          <div class="col-lg-12 col-xl-12 mg-t-10">
            <hr style="border: 1px solid #3a95e4 !important">
          </div>
          
          
           <div class="col-lg-8 col-xl-12 mg-t-10">
             <div class="card">
                <div class="card-header">
                    <span  class="btn btn-danger" onclick="show_funnel1()">SALES FUNNEL</span>&nbsp;&nbsp;
                     <span  class="btn btn-success" onclick="show_funnel()">CHANNEL PARTNER FUNNEL</span>
                </div>
                <div class="card-body pd-lg-25" id="show_funnel1" style="height:500px;">
                    <!--<div class="chart-seven"><canvas id="chartDonut"></canvas></div>-->
                    <div id="chartdiv" ></div>
                </div>
                <div class="card-body pd-lg-25 " id="show_funnel" style="height:500px;display:none;">
                    <!--<div class="chart-seven"><canvas id="chartDonut"></canvas></div>-->
                    <div id="chartdiv1" ></div>
                </div>
             </div>
           </div>
           
          
           <div class="col-lg-12 col-xl-12 mg-t-10">
            <hr style="border: 1px solid #3a95e4 !important">
           </div>
          
           <div class="col-lg-8 col-xl-7 mg-t-10">
            <div class="card" style="height:404px !important;">
              <div class="card-header pd-y-20 d-md-flex align-items-center justify-content-between">
                <h3  class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8" style="font-size:14px">Enquiries</h3>
                <!--<h5 class="mg-b-0 text-center">Enquiries</h5>-->
                <ul class="list-inline d-flex mg-t-20 mg-sm-t-10 mg-md-t-0 mg-b-0">
                  <li class="list-inline-item d-flex align-items-center">
                    <span class="d-block wd-10 ht-10  rounded mg-r-5" style="background-color:rgba(28,225,172, .5)"></span>
                    <span class="tx-sans tx-uppercase tx-10 tx-medium tx-color-03">Active</span>
                  </li>
                  <li class="list-inline-item d-flex align-items-center mg-l-5">
                    <span class="d-block wd-10 ht-10  rounded mg-r-5" style="background-color:rgba(0,23,55, .5)"></span>
                    <span class="tx-sans tx-uppercase tx-10 tx-medium tx-color-03">Dead</span>
                  </li>
                </ul>
              </div><!-- card-header -->
              <div class="card-body pos-relative pd-0">
        
        <div data-label="Example" class="df-example">
          <div class="ht-250 ht-lg-300"><canvas id="chartArea1"></canvas></div>
        </div>
                
                
              </div><!-- card-body -->
            </div><!-- card -->
          </div>
          
          
          
          <div class="col-lg-4 col-xl-5 mg-t-10">
            <div class="card" style="height:95%;">
              <div class="card-header pd-y-20 d-md-flex align-items-center">
                <h3  class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8" style="font-size:14px">Enquiry Status</h3>
              </div>
                  <div data-label="Example" class="df-example">
                      <div id="morrisDonutPP" class="morris-donut-wrapper-demo"></div>
                    </div>
              
                
              </div> 
            
              
            </div>
          </div>
          
          
          <div class="col-lg-12 col-xl-12 mg-t-10">
            <hr style="border: 1px solid red !important">
          </div>
          
          <!---------------------------------------------------->
              <div class="col-md-6 col-lg-4 col-xl-3 mg-t-10 mg-lg-t-0">
                <div class="card" style="height:539px">
                  <div class="card-header">
                    <h3  class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8" style="font-size:14px">Lead</h3>
                  </div><!-- card-header -->
                  <div class="card-body pd-lg-25">
                    <div class="chart-seven" style="margin-top:-80px;"> <div id="morrisDonuts" class="morris-donut-wrapper-demo"></div></div>
                  </div><!-- card-body -->
                  <div class="card-footer pd-20">
                    <div class="row">
                      
              
                      <div class="col-12 mg-t-20">
                         <span  style="font-size:10px;">Cold</span>
                        <div class="d-flex align-items-center">
                          <div class="wd-10 ht-10 rounded-circle bg-teal mg-r-5"></div>
                          <h5 class="tx-normal tx-rubik mg-b-0"> <?php echo $dropld ?></h5><span style="margin-left:44px"><?php echo $valueld ?></span>
                        </div>
                      </div><!-- col -->
                      
                      <div class="col-12 mg-t-20">
                         <span  style="font-size:10px;">Warm</span>
                        <div class="d-flex align-items-center">
                          <div class="wd-10 ht-10 rounded-circle bg-orange mg-r-5"></div>
                          <h5 class="tx-normal tx-rubik mg-b-0"><?php echo $enq_activeld ?></h5><span style="margin-left:49px"><?php echo $enq_valueld ?></span>
                        </div>
                      </div><!-- col -->
                      
                      <div class="col-12 mg-t-20">
                         <span  style="font-size:10px;">Hot</span>
                        <div class="d-flex align-items-center">
                          <div class="wd-10 ht-10 rounded-circle bg-red mg-r-5"></div>
                          <h5 class="tx-normal tx-rubik mg-b-0"><?php echo $total_activeld ?>  </h5><span style="margin-left:49px"><?php echo $total_valueld ?> </span>
                        </div>
                      </div><!-- col -->
                      
                      
                    </div><!-- row -->
                  </div><!-- card-footer -->
                </div><!-- card -->
              </div>
              
               <div class="col-md-6 col-lg-4 col-xl-3 mg-t-10 mg-lg-t-0">
                <div class="card" style="height:539px">
                  <div class="card-header">
                      
                    <h3  class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8" style="font-size:14px">Lead Status</h3>
                    <!--<h5 class="mg-b-0">Lead Stage</h5>-->
                  </div><!-- card-header -->
                  <div class="card-body pd-lg-25">
                    <div class="chart-seven" style="margin-bottom:10px;"><canvas id="chartDonutP"></canvas></div>
                  </div><!-- card-body -->
                  <div class="card-footer">
                    <div class="row">
                      <?php $color= array('#ef1a1a', '#ef9b1a','#06c7e8','#ffbf00','#ff0080','#06c7e8','#ffbf00');
                      $i=0;
                      foreach($lead_stages as $l){
                      $led_status_pi=0;
                               foreach ($all_active->result() as $enquiry){
                               if($enquiry->lead_stage==$l->stg_id){
                                     $led_status_pi++;
                               }   
                              
                 }
                      ?>
                      <div class="col-6" >
                        <span style="font-size:10px;"><?php echo $l->lead_stage_name; ?></span>
                        <div class="d-flex align-items-center">
                          <div class="wd-10 ht-10 rounded-circle mg-r-5" style="background-color:<?php if(!empty($color[$i])){ echo $color[$i];}else{echo $color[0];$i=1;}?>"></div>
                          <h5 class="tx-normal tx-rubik mg-b-0 "><?php echo $led_status_pi; ?></h5>
                        </div>
                      </div><!-- col -->
                      <?php $i++; } ?>
                   <!-- col -->
                      
                      
                    </div><!-- row -->
                  </div><!-- card-footer -->
                </div><!-- card -->
              </div>
              
               <div class="col-md-6 col-lg-6 col-xl-6 mg-t-10 mg-lg-t-0">
                <div class="card" style="height:539px">
                  <div class="card-header">
                    <h3  class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8" style="font-size:14px">Lead Stage By Month</h3>
                    <!--<h5 class="mg-b-0">Lead Stage By Month</h5>-->
                  </div><!-- card-header -->
                  <div class="card-body pd-lg-25" style="height:320px;">
                    <!--<div class="chart-seven"><canvas id="chartDonut"></canvas></div>-->
                    <div class="chart-six"><canvas id="chartBarP"></canvas></div>
                  </div><!-- card-body -->
                  <div class="card-footer pd-20" style="border-top:0px;">
                    <div class="row">
                       <?php $color= array('#ef1a1a', '#ef9b1a','#06c7e8','#ffbf00','#ff0080','#06c7e8','#ffbf00');
                      $i=0;
                      foreach($lead_stages as $l){
                      $led_status_pi=0;
                               foreach ($all_active->result() as $enquiry){
                               if($enquiry->lead_stage==$l->stg_id){
                                     $led_status_pi++;
                               }   
                              
                 }
                      ?>
                      <div class="col-3 mg-t-20">
                        <span  style="font-size:10px;"><?php echo $l->lead_stage_name; ?></span>
                        <div class="d-flex align-items-center">
                          <div class="wd-10 ht-10 rounded-circle mg-r-5" style="background-color:<?php if(!empty($color[$i])){ echo $color[$i];}else{echo $color[0];$i=1;}?>"></div>
                          <h5 class="tx-normal tx-rubik mg-b-0"><?php echo $led_status_pi; ?></h5>
                        </div>
                      </div><!-- col -->
                     <?php $i++; } ?>
                      
                      
                    </div><!-- row -->
                  </div><!-- card-footer -->
                </div><!-- card -->
              </div>
              
              
              
              <div class="col-lg-12 col-xl-12 mg-t-10">
                <hr style="border: 1px solid #37a000 !important">
              </div>
              
              <!------------------------------------------------- Sales status bar ------------------------------------------------------>
                <div class="col-lg-4 col-xl-5 mg-t-10">
                        <div class="card" style="height:383px">
                          <div class="card-header pd-t-20 pd-b-0 bd-b-0">
                             <h3  class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8" style="font-size:14px">Sales by States</h3>
                            <!--<h5 class="mg-b-5">Sales by States</h5>-->
                            <!--<p class="tx-12 tx-color-03 mg-b-0">Number of customers who have active subscription with you.</p>-->
                          </div><!-- card-header -->
                          <div class="card-body pd-20">
                            <div class="chart-two mg-b-20">
                              <div id="map" class="" style="height:312px !important"></div>
                            </div><!-- chart-two -->
                            <div class="row">
                            </div><!-- row -->
                          </div><!-- card-body -->
                        </div><!-- card -->
                    </div>
                
                <div class="col-lg-8 col-xl-7 mg-t-10">
                        <div class="card" style="height:383px;">
                          <div class="card-header pd-y-20 d-md-flex align-items-center center-content-between">
                            <!--<h5 class="mg-b-0">Month Wise Sale Revenue</h5>-->
                            <h3  class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8" style="font-size:14px">Month Wise Sale Revenue</h3>
                          </div>
                          <div class="card-body pos-relative pd-0 table-responsive" style="margin-top: 27px">
                            <table class="table table-bordered table-dashboard table-dashboard-one text-center">
                                <thead style="font-weight:bold">
                                  <tr>
                                    <th>Month</th>
                                    <th>Total Revenue</th>
                                    <th>Payments Received</th>
                                    <th>Payments Pending</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>July</td>
                                    <td>333180</td>
                                    <td>202120</td>
                                    <td>202120</td>
                                  </tr>
                                  <tr>
                                    <td>June</td>
                                    <td>000</td>
                                    <td>000</td>
                                    <td>000</td>
                                  </tr>
                                  <tr>
                                    <td>May</td>
                                    <td>000</td>
                                    <td>000</td>
                                    <td>000</td>
                                  </tr>
                                  <tr>
                                    <td>April</td>
                                    <td>000</td>
                                    <td>000</td>
                                    <td>000</td>
                                  </tr>
                                  <tr>
                                    <td>March</td>
                                    <td>000</td>
                                    <td>000</td>
                                    <td>000</td>
                                  </tr>
                                   <tr>
                                    <td>February</td>
                                    <td>000</td>
                                    <td>000</td>
                                    <td>000</td>
                                  </tr>
                                  <tr>
                                    <td>January</td>
                                    <td>000</td>
                                    <td>000</td>
                                    <td>000</td>
                                  </tr>
                                </tbody>
                              </table>
                          </div>
                        </div>
                      </div>
                      
              
              <!------------------------------------------------------------------------------------------------------------------------->
              
         
        </div><!-- row -->
        
        </div>
        
        
        <!-------------------------------------------------------Customer success Dashboard --------------------------------------------->
        <div class="row row-xs" style="display:none;">
          <div class="col-sm-6 col-lg-3">
            <div class="card card-body">
                
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8" style="font-size: 14px;"><?= display('enquiry') ?><span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 total-qnq" style="margin-left: 25%;font-size: 14px;"></span><span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right enq-total" style="font-size: 14px;"></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Active <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 enq-active" style="margin-left: 34%;"></span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right total-active"></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Dead <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 total-dead" style="margin-left: 37%;"></span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right qne-deadd"></span></h6><br>
            
              
              <div class="d-flex d-lg-block d-xl-flex align-items-end">
                <!--<h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1"><?php echo (!empty($total_enquiry->total) ? $total_enquiry ->total : 0) ?></h3>-->
                <!--<p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium tx-success">1.2% <i class="icon ion-md-arrow-up"></i></span> than last week</p>-->
              </div>
              <div class="chart-three">
                  <div id="flotChart3" class="flot-chart ht-30"></div>
                </div><!-- chart-three -->
            </div>
          </div><!-- col -->
          <div class="col-sm-6 col-lg-3 mg-t-10 mg-sm-t-0">
            <div class="card card-body">
                
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8" style="font-size: 14px;"><?= display('lead') ?><span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 all-lead" style="margin-left: 29%;font-size: 14px;"></span><span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right led-revenue" style="font-size: 14px;"></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Hot <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 hot" style="margin-left: 34%;"></span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right hot-total"></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">warm <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 warm" style="margin-left: 29%;"></span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right warm-total"></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">cold <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 cold" style="margin-left: 32%;"></span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right cold-total"></span></h6>
              
              <div class="d-flex d-lg-block d-xl-flex align-items-end">
                <!--<h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1"><?php echo (!empty($leads->total) ? $leads->total : 0) ?></h3>
                <p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium tx-danger">0.7% <i class="icon ion-md-arrow-down"></i></span> than last week</p>-->
              </div>
              <div class="chart-three">
                  <div id="flotChart4" class="flot-chart ht-30"></div>
                </div><!-- chart-three -->
            </div>
          </div><!-- col -->
          <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
            <div class="card card-body">
              
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8" style="font-size: 14px;"><?= display('doctor') ?><span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 total-client" style="margin-left: 31%;font-size: 14px;"></span><span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right r-total" style="font-size: 14px;"></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Inst Comp <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 total-installation" style="margin-left: 26%;"></span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right total-rev"></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Inst pending <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 inst-penddings" style="margin-left: 18%;"></span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right pndding"></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Active Ticket <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1" style="margin-left: 19%;">20</span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right">1200</span></h6>
              
              <!--<h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8"><?= display('doctor') ?></h6>-->
              <div class="d-flex d-lg-block d-xl-flex align-items-end">
                <!--<h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1"><?php echo (!empty($clients->total) ? $clients->total : 0) ?></h3>-->
                <!--<p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium tx-danger">0.3% <i class="icon ion-md-arrow-down"></i></span> than last week</p>-->
              </div>
              <div class="chart-three">
                  <div id="flotChart5" class="flot-chart ht-30"></div>
                </div><!-- chart-three -->
            </div>
          </div><!-- col -->
          <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
            <div class="card card-body">
                
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8" style="font-size: 14px;">Channel Partner<span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1" style="margin-left: 11%;font-size: 14px;">20</span><span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right" style="font-size: 14px;"><b>1000</b></span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Tycoon <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1" style="margin-left: 45%;">20</span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right">1000</span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Magnet <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1" style="margin-left: 44%;">20</span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right">1200</span></h6>
              <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Ranger <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1" style="margin-left: 45%;">20</span> <span class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1 pull-right">1200</span></h6>
              
              <div class="d-flex d-lg-block d-xl-flex align-items-end">
                <!--<h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1"><?php echo (!empty($all_invoices->total) ? $all_invoices->total : 0) ?></h3>-->
                <!--<p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium tx-success">2.1% <i class="icon ion-md-arrow-up"></i></span> than last week</p>-->
              </div>
              <div class="chart-three">
                  <div id="flotChart6" class="flot-chart ht-30"></div>
                </div><!-- chart-three -->
            </div>
          </div><!-- col -->
          <div class="col-lg-8 col-xl-7 mg-t-10">
            <div class="card">
              <div class="card-header pd-y-20 d-md-flex align-items-center justify-content-between">
                <h6 class="mg-b-0">Enquiries</h6>
              </div><!-- card-header -->
              <div class="card-body pos-relative pd-0">
                  
                <div class="chart-one">
                  <!--<div id="flotChart" class="flot-chart"></div>-->
                  <!--<div class="chart-six"><canvas id="chartBark"></canvas></div>-->
                  <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                  <!--<div id="chartBarD" class="flot-chart"></div>-->
                </div><!-- chart-one -->
              </div><!-- card-body -->
            </div><!-- card -->
          </div>
          <div class="col-lg-4 col-xl-5 mg-t-10">
            <div class="card">
              <div class="card-header pd-t-20 pd-b-0 bd-b-0">
                <h6 class="mg-b-5">Enquiry Status</h6>
                <!--<p class="tx-12 tx-color-03 mg-b-0">Number of customers who have active subscription with you.</p>-->
              </div><!-- card-header -->
              <div class="card-body pd-20">
                <div class="chart-two mg-b-20">
                  <!--<div id="map" class="ht-200"></div>-->
                  <div id="chartContainer2" style="height: 300px; width: 100%;"></div>
                </div><!-- chart-two -->
              </div><!-- card-body -->
            </div><!-- card -->
          </div>
          
          <!---------------------------------------------------->
              <div class="col-md-6 col-lg-4 col-xl-3 mg-t-10 mg-lg-t-0">
                <div class="card">
                  <div class="card-header">
                    <h6 class="mg-b-0">Lead Status</h6>
                  </div><!-- card-header -->
                  <div class="card-body pd-lg-25">
                    <div class="chart-seven"><canvas id="chartDonut"></canvas></div>
                  </div><!-- card-body -->
                  <div class="card-footer pd-20">
                    <div class="row">
                      
                      <div class="col-12 mg-t-20">
                        <p class="tx-10 tx-uppercase tx-medium tx-color-03 tx-spacing-1 mg-b-5">COLD</p>
                        <div class="d-flex align-items-center">
                          <div class="wd-10 ht-10 rounded-circle bg-teal mg-r-5"></div>
                          <h5 class="tx-normal tx-rubik mg-b-0 cold"> <!--<small class="tx-color-04">30%</small>--></h5>
                        </div>
                      </div><!-- col -->
                      
                      <div class="col-12 mg-t-20">
                        <p class="tx-10 tx-uppercase tx-medium tx-color-03 tx-spacing-1 mg-b-5">Warm</p>
                        <div class="d-flex align-items-center">
                          <div class="wd-10 ht-10 rounded-circle bg-orange mg-r-5"></div>
                          <h5 class="tx-normal tx-rubik mg-b-0 warm"> <!--<small class="tx-color-04">25%</small>--></h5>
                        </div>
                      </div><!-- col -->
                      
                      <div class="col-12 mg-t-20">
                        <p class="tx-10 tx-uppercase tx-medium tx-color-03 tx-spacing-1 mg-b-5">HOT</p>
                        <div class="d-flex align-items-center">
                          <div class="wd-10 ht-10 rounded-circle bg-red mg-r-5"></div>
                          <h5 class="tx-normal tx-rubik mg-b-0 hot"> <!--<small class="tx-color-04">25%</small>--></h5>
                        </div>
                      </div><!-- col -->
                      
                      
                    </div><!-- row -->
                  </div><!-- card-footer -->
                </div><!-- card -->
              </div>
              
              <div class="col-lg-8 col-xl-9">
                <div class="card">
                  <div class="card-header bd-b-0 pd-t-20 pd-lg-t-25 pd-l-20 pd-lg-l-25 d-flex flex-column flex-sm-row align-items-sm-start justify-content-sm-between">
                    <div>
                      <h6 class="mg-b-5">Lead Stage</h6>
                      <!--<p class="tx-12 tx-color-03 mg-b-0">Audience to which the users belonged while on the current date range.</p>-->
                    </div>
                    <!--<div class="btn-group mg-t-20 mg-sm-t-0">
                      <button class="btn btn-xs btn-white btn-uppercase">Day</button>
                      <button class="btn btn-xs btn-white btn-uppercase">Week</button>
                      <button class="btn btn-xs btn-white btn-uppercase active">Month</button>
                    </div><!-- btn-group -->
                  </div><!-- card-header -->
                  <div class="card-body pd-lg-25">
                    <div class="row align-items-sm-end">
                       
                      <div class="col-lg-5 col-xl-4 mg-t-30 mg-lg-t-0">
                        <div class="row">
                        
                        </div><!-- row -->
                        <div class="card-footer pd-20">
                            
                    <div class="row">
                      
                      <div class="col-6 mg-t-20">
                        <p class="tx-10 tx-uppercase tx-medium tx-color-03 tx-spacing-1 mg-b-5">DEMO</p>
                        <div class="d-flex align-items-center">
                          <div class="wd-10 ht-10 rounded-circle bg-red mg-r-5"></div>
                          <h5 class="tx-normal tx-rubik mg-b-0">6 <!--<small class="tx-color-04">30%</small>--></h5>
                        </div>
                      </div><!-- col -->
                      
                      <div class="col-6 mg-t-20">
                        <p class="tx-10 tx-uppercase tx-medium tx-color-03 tx-spacing-1 mg-b-5">LOOPING DIAGRAM</p>
                        <div class="d-flex align-items-center">
                          <div class="wd-10 ht-10 rounded-circle bg-orange mg-r-5"></div>
                          <h5 class="tx-normal tx-rubik mg-b-0">0 <!--<small class="tx-color-04">25%</small>--></h5>
                        </div>
                      </div><!-- col -->
                      
                      <div class="col-6 mg-t-20">
                        <p class="tx-10 tx-uppercase tx-medium tx-color-03 tx-spacing-1 mg-b-5">CIRCUIT SHEET</p>
                        <div class="d-flex align-items-center">
                          <div class="wd-10 ht-10 rounded-circle bg-teal mg-r-5"></div>
                          <h5 class="tx-normal tx-rubik mg-b-0">0 <!--<small class="tx-color-04">25%</small>--></h5>
                        </div>
                      </div><!-- col -->
                      
                      <div class="col-6 mg-t-20">
                        <p class="tx-10 tx-uppercase tx-medium tx-color-03 tx-spacing-1 mg-b-5">PURCHASE ORDER</p>
                        <div class="d-flex align-items-center">
                          <div class="wd-10 ht-10 rounded-circle  mg-r-5" style="background:#0040ff"></div>
                          <h5 class="tx-normal tx-rubik mg-b-0">1 <!--<small class="tx-color-04">25%</small>--></h5>
                        </div>
                      </div><!-- col -->
                      
                      <div class="col-6 mg-t-20">
                        <p class="tx-10 tx-uppercase tx-medium tx-color-03 tx-spacing-1 mg-b-5">COMPLETE</p>
                        <div class="d-flex align-items-center">
                          <div class="wd-10 ht-10 rounded-circle  mg-r-5" style="background:#bf00ff"></div>
                          <h5 class="tx-normal tx-rubik mg-b-0">1 <!--<small class="tx-color-04">25%</small>--></h5>
                        </div>
                      </div><!-- col -->
                      
                      
                    </div><!-- row -->
                  </div><!-- card-footer -->
    
                      </div>    
                        
                      <div class="col-lg-7 col-xl-8" style="margin-bottom:72px;">
                        <div class="chart-six"><canvas id="chartBarP"></canvas></div>
                      </div>
                      
                    </div>
                  </div><!-- card-body -->
                </div><!-- card -->
              </div>
              
              <!------------------------------------------------- Sales status bar ------------------------------------------------------>
                
                <div class="col-lg-8 col-xl-7 mg-t-10">
                        <div class="card">
                          <div class="card-header pd-y-20 d-md-flex align-items-center justify-content-between">
                            <h6 class="mg-b-0">Month Wise Sale Revenue</h6>
                          </div>
                          <div class="card-body pos-relative pd-0">
                            <table class="table table-bordered table-dashboard table-dashboard-one">
                                <thead>
                                  <tr>
                                    <th>Month</th>
                                    <th>Total Revenue</th>
                                    <th>Payments Received</th>
                                    <th>Payments Pending</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>July</td>
                                    <td>333180</td>
                                    <td>202120</td>
                                    <td>202120</td>
                                  </tr>
                                  <tr>
                                    <td>June</td>
                                    <td>000</td>
                                    <td>000</td>
                                    <td>000</td>
                                  </tr>
                                  <tr>
                                    <td>March</td>
                                    <td>000</td>
                                    <td>000</td>
                                    <td>000</td>
                                  </tr>
                                </tbody>
                              </table>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4 col-xl-5 mg-t-10">
                        <div class="card">
                          <div class="card-header pd-t-20 pd-b-0 bd-b-0">
                            <h6 class="mg-b-5">Sales by states</h6>
                            <!--<p class="tx-12 tx-color-03 mg-b-0">Number of customers who have active subscription with you.</p>-->
                          </div><!-- card-header -->
                          <div class="card-body pd-20">
                            <div class="chart-two mg-b-20">
                              <div id="map" class="ht-200"></div>
                            </div><!-- chart-two -->
                            <div class="row">
                              <div class="table-responsive">
                              <table class="table table-borderless table-dashboard table-dashboard-one">
                                <thead>
                                  <tr>
                                    <th class="wd-40">Region</th>
                                    <th class="wd-25 text-right">Count</th>
                                    <th class="wd-35 text-right">Value</th>
                                  </tr>
                                </thead>
                                <!--<tbody id="table-data">-->
                                <tbody>
                                    <tr>
                                        <td class="wd-40">Delhi</td>
                                        <td class="wd-25 text-right">10</td>
                                        <td class="wd-35 text-right">1000000</td>
                                    </tr>
                                  
                                </tbody>
                              </table>
                            </div><!-- table-responsive -->
                            </div><!-- row -->
                          </div><!-- card-body -->
                        </div><!-- card -->
                    </div>
              
              <!------------------------------------------------------------------------------------------------------------------------->
              
         
        </div><!-- row -->
        
        
        <!------------------------------------------------------------------------------------------------------------------------------->
        
        
        
      </div><!-- container -->
    </div><!-- content -->

    




   

    <script src="<?php echo base_url()?>custom_dashboard/lib/jquery.flot/jquery.flot.js"></script>
    <script src="<?php echo base_url()?>custom_dashboard/lib/jquery.flot/jquery.flot.stack.js"></script>
    <script src="<?php echo base_url()?>custom_dashboard/lib/jquery.flot/jquery.flot.resize.js"></script>
    <script src="<?php echo base_url()?>custom_dashboard/lib/chart.js/Chart.bundle.min.js"></script>
    <script src="<?php echo base_url()?>custom_dashboard/lib/jqvmap/jquery.vmap.min.js"></script>
    <script src="<?php echo base_url()?>custom_dashboard/lib/jqvmap/maps/jquery.vmap.usa.js"></script>
    
    <script src="<?php echo base_url()?>custom_dashboard/assets/js/dashforge.sampledata.js"></script>
    <script src="<?php echo base_url()?>assets/js/custom-chart.js"></script>
    <script src="<?php echo base_url()?>custom_dashboard/lib/morris.js/morris.min.js"></script>
    <!----- custom link ---------------->
    <!--<script type="text/javascript" src="https://www.amcharts.com/lib/3/ammap.js"></script>-->
    <script src="<?php echo base_url()?>custom_dashboard/assets/js/india-js-map.js"></script>
    <script type="text/javascript" src="https://www.amcharts.com/lib/3/maps/js/indiaLow.js"></script>
    <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
     <script src="<?php echo base_url()?>custom_dashboard/lib/raphael/raphael.min.js"></script>
    <!--<script src="<?php echo base_url()?>custom_dashboard/assets/js/chart.morris.js"></script>-->
    
    <script src="<?php echo base_url()?>custom_dashboard/lib/peity/jquery.peity.min.js"></script>
    
     <script src="<?php echo base_url()?>custom_dashboard/assets/js/chart.chartjs.js"></script>
     
     <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
     
     <!--------------amcharts funnel-------------------------------------------------------->
     <script src="https://www.amcharts.com/lib/4/core.js"></script>
     <script src="https://www.amcharts.com/lib/4/charts.js"></script>
     <script src="https://www.amcharts.com/lib/4/themes/material.js"></script>
     <script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
     <!--------------End here -------------------------------------------------------------->

 <?php if(user_access('60')==true||user_access('70')==true||user_access('80')==true){?>
        <script>
        function sales_dash()
        {    
            $("#content_tabs").html('');
            $("#content_tabs1").css('display', 'block');
        }
       </script>
<?php }else if(user_access('100')==true ||user_access('101')==true||user_access('102')==true||user_access('103')==true||user_access('104')==true||user_access('105')==true||user_access('106')==true||user_access('110')==true||user_access('111')==true||user_access('112')==true||user_access('113')==true||user_access('114')==true||user_access('115')==true||user_access('116')==true){?>
                     
    ?>
    <script type='text/javascript'>
    $(window).load(function(){
      $("#content_tabs").load('<?php echo base_url()?>dashboard/sales_dashboard'); 
       $("#content_tabs1").css('display', 'none');
    });  
   </script>
                     <?php  }?>
   <script>
       function changeMenu(menu,submenu,options)
        {    
            $("#content_tabs").load('<?php echo base_url()?>/'+menu+'/'+submenu);
            $("#content_tabs1").css('display', 'none');
            
       }
       function show_funnel1(){
      $("#show_funnel").css('display', 'none'); 
            $("#show_funnel1").css('display', 'block');  
       }
       function show_funnel(){
      $("#show_funnel1").css('display', 'none'); 
            $("#show_funnel").css('display', 'block');  
       }
       </script>
       
          
       
       
    <script>
     
      //Get drop down value..
      function getEventTarget(e) {
            e = e || window.event;
            return e.target || e.srcElement; 
        }
        
        var ul = document.getElementById('droupdown-value');
            ul.onclick = function(event) {
        var target = getEventTarget(event);
            //alert(target.innerText);
            
            document.getElementById("selectedcountry").innerHTML = target.innerText;
        };
        
        var My_ul = document.getElementById('selecteddashboard');
        
        My_ul.onclick = function(event){
            
            var mytarget = getEventTarget(event);
            document.getElementById("selectdashboard").innerHTML = mytarget.innerText;
        }
      
    </script>
    <script>
        
        $(function(){            
            //Getting enquiry data..
            $.ajax({                
                url : '<?php echo base_url()?>Report/enquiry_statitics_report',
                type: 'POST',
                success:function(data){                    
                    var obj = JSON.parse(data);                  
                    var html = '';                    
                    for(var i=0; i < (obj.length); i++){                        
                        html +='<tr>';                        
                            html +='<td class="tx-medium">'+obj[i].state+'</td>';
                            html +='<td class="text-right">'+obj[i].city+'</td>';
                            html +='<td class="text-right">'+obj[i].total+'</td>';                            
                        html +='</tr>';                        
                    }                    
                    //show data in table
                    $('#table-data').html(html);
                }                
            });            
            //Getting Lead data
            $.ajax({                
                url : '<?php echo base_url(); ?>/Report/lead_statitics_report',
                type: 'POST',
                success:function(data){                    
                    var obj = JSON.parse(data);                    
                    var html='';                    
                    $(".cold").html(obj['coldid'].coldid); //Cold total                    
                    $(".warm").html(obj['warmid'].warmid);
                    //Warm total
                    console.log(obj['hotid'].hotid);
                    $(".hot").html(obj['hotid'].hotid);
                    $(".total_revnue_lead").html(obj['total_revnue_lead'].total_revnue_lead);
                    $(".total_lead").html(obj['total_lead'].total_lead);                     
                    $(".cold-total").html(obj['cold'].cold);
                    $(".hot-total").html(obj['hot'].hot);
                    $(".warm-total").html(obj['warm'].warm);          
                    $("#circuit_sheet").html(obj['circuit_sheet'].circuit_sheet); // total circuit_sheet            
                    $("#boq").html(obj['circuit_sheet'].boq);  // total boq.                    
                    $("#po").html(obj['po'].po);                    
                    $(".total-qnq").html(obj['enquiry'][0].total); //Total enquiry..
                    $(".enq-total").html(obj['enquiry'][0].total_value).css('font-weight','bold');
                    //Enquiry total value...
                    $(".enq-active").html(obj['enquiry'][0].active) // Active enquiry..                    
                    $(".total-dead").html(obj['enquiry'][0].dead);
                    $(".total-active").html(obj['active'].total);                    
                    if(obj['dead'].total==null){                        
                       var dead = 0;                        
                    }else{                        
                         var dead = obj['dead'].total;
                     }
                    
                    $(".qne-deadd").html(dead);                    
                    for(var i =0; i < (obj['stage'].length); i++){                        
                        html +='<p>'+(obj['stage'][i].lead_stage_name)+'</p>';
                    }                    
                    $("#stage").html(html);                    
                }                
            });
        });        
    </script>
    <script type="text/javascript">       
    	new Morris.Donut({
            element: 'morrisDonutPP',
            data: [
               <?php 
               $num_led=0;
               foreach ($leadsource as $post):
                    		$num_led=0;
                                 foreach ($all_enquery->result() as $enquiry){
                                 if($post->lsid==$enquiry->enquiry_source){
                                     $num_led++;
                 }        
                 }
          
          ?>
              {label: '<?php echo $post->lead_name;?>', value: <?php echo $num_led;?>},
           
               <?php endforeach; ?>
            ],
            colors: ['#560bd0', '#007bff','#00cccc','#74DE00'],
            resize: true,
          });
    
        
    </script>
    <script type="text/javascript">
      
        
    	new Morris.Donut({
            element: 'morrisDonuts',
            data: [               
              {label: 'COLD', value: <?php echo $dropld;?>},
           {label: 'Warm', value: <?php echo $enq_activeld;?>},
           {label: 'HOT', value: <?php echo $total_activeld;?>}             
            ],
            colors: ['#00cccc', '#fd7e14','#E5343D ','#74DE00'],
            resize: true,
          }); 
        
    </script>
    <div style="float:right;margin-right:100px">
    <?php  $ab=''; foreach($lead_stages as $l){
                     
                              
                               $ab.="'".$l->lead_stage_name."',";
                               } 
                               $cd='';
                               foreach($lead_stages as $l){
                               $led_status_pi=0;
                               foreach ($all_active->result() as $enquiry){
                               if($enquiry->lead_stage==$l->stg_id){
                                     $led_status_pi++;
                               }
                               
                               } $cd.="'".$led_status_pi."',"; }
                               
                               
                               
                               ?>
    <script>
          
               var datapie2 = {
                  labels:[<?php  echo substr($ab,0,-1);?>],
                  
                  datasets: [{
                    data: [<?php echo substr($cd,0,-1);?>],
                    
                    backgroundColor: ['#ef1a1a', '#ef9b1a','#06c7e8','#ffbf00','#ff0080','#06c7e8','#ffbf00']
                  }]
                };
                
        
                var optionpie2 = {
                  maintainAspectRatio: false,
                  responsive: true,
                  legend: {
                    display: false,
                  },
                  animation: {
                    animateScale: true,
                    animateRotate: true
                  }
                };
        
                // For a pie chart
                var ctx3 = document.getElementById('chartDonutP');
                var myDonutChart = new Chart(ctx3, {
                  type: 'doughnut',
                  data: datapie2,
                  options: optionpie2
                });
            

    </script>
    <?php 
                              $Account=0;
                               if(!empty($all_Active_clients->result())){
                                 $Account= $all_Active_clients->num_rows();
                               }$partner=0;
                                foreach ($all_Active_clients->result() as $enquiry){
                               if($enquiry->lead_stage==$l->stg_id){
                                     $led_status_pi++;
                               }
                               
                               }
                                ?>
    <script>
        
                am4core.useTheme(am4themes_material);
                am4core.useTheme(am4themes_animated);
                // Themes end
                
                var chart = am4core.create("chartdiv", am4charts.SlicedChart);
                chart.hiddenState.properties.opacity = 0; // this makes initial fade in effect
                
                chart.data = [{
                    "name": "Enquiries",
                    "value":<?php echo $total_active;?>
                }, {
                    "name": "Lead",
                    "value": <?php echo $wormtotal_valueld;?>
                }, {
                    "name": "Clients",
                    "value": <?php echo $Account;?>
                }, {
                    "name": "Installation",
                    "value": 0
                }, {
                    "name": "Tickets",
                    "value": 0
                }];
                
                var series = chart.series.push(new am4charts.FunnelSeries());
                series.colors.step = 2;
                series.dataFields.value = "value";
                series.dataFields.category = "name";
                series.alignLabels = true;
                
                series.labelsContainer.paddingLeft = 15;
                series.labelsContainer.width = 200;
                
                //series.orientation = "horizontal";
                //series.bottomRatio = 1;
                
                chart.legend = new am4charts.Legend();
                chart.legend.position = "left";
                chart.legend.valign = "bottom";
                chart.legend.margin(5,5,20,5);
        </script>
        <script>       
                 am4core.useTheme(am4themes_material);
                am4core.useTheme(am4themes_animated);
                // Themes end
                
                var chart = am4core.create("chartdiv1", am4charts.SlicedChart);
                chart.hiddenState.properties.opacity = 0; // this makes initial fade in effect
                
                chart.data = [{
                    "name": "Enquiries",
                    "value":8
                }, {
                    "name": "Lead",
                    "value": 8
                }, {
                    "name": "Clients",
                    "value": 5
                }, {
                    "name": "Installation",
                    "value": 8
                }, {
                    "name": "Tickets",
                    "value":9
                }];
                
                var series = chart.series.push(new am4charts.FunnelSeries());
                series.colors.step = 2;
                series.dataFields.value = "value";
                series.dataFields.category = "name";
                series.alignLabels = true;
                
                series.labelsContainer.paddingLeft = 15;
                series.labelsContainer.width = 200;
                
                //series.orientation = "horizontal";
                //series.bottomRatio = 1;
                
                chart.legend = new am4charts.Legend();
                chart.legend.position = "left";
                chart.legend.valign = "bottom";
                chart.legend.margin(5,5,20,5);
    </script>
                
                
         
  </body>
</html>
