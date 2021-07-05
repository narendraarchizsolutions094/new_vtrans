<!DOCTYPE HTML PUBLIC "-/W3C/DTD HTML 4.01/EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html"
         charset="UTF-8">
      <title></title>
      <style type="text/css">
      </style>
   </head>
   <body style="margin:0; padding:0;">
      <table border="0" cellspacing="0" cellpadding="0" bgcolor="#edeff1"
         style="margin: 0px; padding-top: 40px; padding-bottom: 80px; width:
         100%;">
         <tbody>
            <tr>
               <td>
                  <!-- Email Container --> <!-- NOTE: Mailer width 640px -->
                  <table class="container" align="center" border="0" cellspacing="0"
                     cellpadding="0" style="width: 640px;">
                     <!-- @row 1 --> <!-- Preheader -->
                     <tbody>
                        <!-- <tr>
                           <td>
                              <table bgcolor="#e9e9e9" border="0" cellspacing="0" cellpadding="0"
                                 style="width: 100%;">
                                 <tbody>
                                    <tr>
                                       <td valign="top" class="pre-header" style="text-align: center;">
                                          <p style="color: #373434; font-size: 11px; font-family: tahoma,
                                             sans-serif;">If you are having trouble viewing this e-mail, <a
                                             style="color: #373434; text-decoration: none;"
                                             href=""
                                             target="_blank"><strong>click here</strong></a></p>
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr> --> 
                        <!-- end@Preheader --> <!-- end@ row 1 --> <!-- header -->
                        <tr>
                           <td valign="top" style="text-align: center; padding: 20px 50px;
                              background-color: #ffffff;">
                              <!-- Intro content -->
                              <p style="font-family: 'Open Sans', sans-serif; color: #333333; font-size:
                                 19px; font-weight: 500; width: 90%; margin: 0 auto;">
                                 <?php
                                 if(!empty($filters['process_id'])){
                                    if($filters['process_id'] == 198){
                                       $logo_url = 'https://v-trans.thecrm360.com/assets/images/v-xpress-logo.png';                                       
                                    }else{
                                       $logo_url = 'https://v-trans.thecrm360.com/assets/images/vtrans_logo.png';
                                    }
                                 }else{
                                    $logo_url = 'https://v-trans.thecrm360.com/assets/images/vtrans_logo.png';
                                 }
                                 ?>
                                 <img
                                 src="<?=$logo_url?>"
                                 alt="" style="height: 100px; width: auto;" /></p>
                           </td>
                        </tr>
                        <tr>
                           <td valign="top" style="text-align: left; padding: 20px 50px;
                              background-color: #ffffff;">
                              <!-- Intro content -->
                              <p style="font-family: 'Open Sans', sans-serif; color: #333333; font-size:
                                 17px; font-weight: 500; margin: 0 auto;"> Dear <?= $userName ?>, </p>
                           </td>
                        </tr>
                        <tr>
                           <td valign="top" style="text-align: left; padding: 20px 50px;
                              background-color: #ffffff;">
                              <!-- Intro content -->
                              <p style="font-family: 'Open Sans', sans-serif; color: #333333; font-size:
                                 17px; font-weight: 500; margin: 0 auto;">
                                 Greetings for the day.
                                 <br><br>
                                 <?php
                                 if(!empty($filters['process_id'])){
                                    if($filters['process_id'] == 198){
                                       $crm = 'V-Xpress';
                                    }else{
                                       $crm = 'V-Trans';
                                    }
                                 }else{
                                    $crm = 'V-Trans';
                                 }
                                 ?>
                                 Please refer to the below link to check for daily report of <?=$crm?> CRM dated <?php echo date('Y-m-d',strtotime('-1 days')); ?>.
                                 <br>
                                 <br>

                                 <a href="<?= $links ?>" target="_BLANK">
                                 <button type="button" style="margin:4px;
                                    background-color:green;
                                    border-radius:4px;
                                    border:1px solid #D0D0D0;
                                    overflow:auto;
                                    color:white;
                                    float:left;" >Click Here to view </button></a>
                                <br>
                                <br>
                              <div class="row row text-center short_dashboard" id="active_class">
                                 <section id="statistic" class="statistic-section one-page-section" style=" padding-top: 70px;
                                    padding-bottom: 70px;
                                    ">
                                    <div>
                                       <div style="display: flex; -ms-flex-wrap: wrap; flex-wrap: wrap;  text-align: center!important;">
                                          <div style="flex: 0 0 140px;
                                             max-width: 140px;width: 140px; border:solid 1px black;-webkit-box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); 
                                             box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); margin:5px;background-color:  linear-gradient(to right, #1a75ff , white );">
                                             <div class="counter">
                                                <h2 class="timer count-title count-number">Created</h2>
                                                <div class="stats-line-black"></div>
                                                <p class="stats-text"><?= $created ?></p>
                                             </div>
                                          </div>
                                          <div style="flex: 0 0 140px;
                                             max-width: 140px;width: 140px; border:solid 1px black;-webkit-box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); 
                                             box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); margin:5px;background-color:  linear-gradient(to right, #1a75ff , white );">
                                             <div class="counter">
                                                <h2 class="timer count-title count-number">Assigned</h2>
                                                <div class="stats-line-black"></div>
                                                <p class="stats-text"><?= $assigned ?></p>
                                             </div>
                                          </div>
                                          <div style="flex: 0 0 140px;
                                             max-width: 140px;width: 140px; border:solid 1px black;-webkit-box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); 
                                             box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); margin:5px; background-color:  linear-gradient(to right, #1a75ff , white );">
                                             <div class="counter">
                                                <h2 class="timer count-title count-number">Updated
                                                </h2>
                                                <div ></div>
                                                <p><?= $updated ?></p>
                                             </div>
                                          </div>
                                       </div>
                                       <div style="display: flex; -ms-flex-wrap: wrap; flex-wrap: wrap;  text-align: center!important;">
                                          <div style="flex: 0 0 140px;
                                             max-width: 140px;width: 140px; border:solid 1px black;-webkit-box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); 
                                             box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); margin:5px;background-color:  linear-gradient(to right, #1a75ff , white );">
                                             <div class="counter">
                                                <i class="fa fa-clock-o fa-2x stats-icon"></i>
                                                <h2 class="timer count-title count-number">Active</h2>
                                                <div class="stats-line-black"></div>
                                                <p class="stats-text"><?= $followups ?></p>
                                             </div>
                                          </div>
                                          <div style="flex: 0 0 140px;
                                             max-width: 140px;width: 140px; border:solid 1px black;-webkit-box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); 
                                             box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); margin:5px;background-color:  linear-gradient(to right, #1a75ff , white );">
                                             <div class="counter">
                                                <i class="fa fa-laptop fa-2x stats-icon"></i>
                                                <h2 class="timer count-title count-number">Closed</h2>
                                                <div class="stats-line-black"></div>
                                                <p class="stats-text"><?= $all_closed ?></p>
                                             </div>
                                          </div>
                                          <div style="flex: 0 0 140px;
                                             max-width: 140px;width: 140px; border:solid 1px black;-webkit-box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); 
                                             box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); margin:5px;background-color:  linear-gradient(to right, #1a75ff , white );">
                                             <div class="counter">
                                                <i class="fa fa-laptop fa-2x stats-icon"></i>
                                                <h2 class="timer count-title count-number">Pending</h2>
                                                <div class="stats-line-black"></div>
                                                <p class="stats-text"><?= $pending ?></p>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </section>
                              </div>
                              <br>
                              <br>
                                 <br>
                                 </p>
                           </td>
                        </tr>
                        <tr>
                        <!-- <td valign="top" style="text-align: left; padding: 20px 50px;
                           background-color: #ffffff;"> 
                           <p style="font-family: 'Open Sans', sans-serif; color: #333333; font-size:
                           17px; font-weight: 500; margin: 0 auto;">Sincerely,<br /> </p>
                           </td> -->
                        </tr>
                        <!-- end@row Footer --> <!-- @row Footer -->
                        <!-- <tr bgcolor="#2c2b2b">
                        <td style="padding: 55px; text-align: center;">
                        <p style="text-align: center; font-family: 'Open Sans', sans-serif; color:
                           #ffffff; font-size: 16px; padding: 0; letter-spacing: 2px; margin: 0;
                           font-weight: normal; padding-top: 10px; padding-bottom: 40px;">NEED
                        HELP?</p>
                        <div style="width: 90%; height: 1px; margin: 0 auto; background-color:
                           #393838;"></div>
                        <p style="font-family: 'Open Sans', sans-serif; color: #ffffff; font-size:
                           16px; padding: 0; letter-spacing: 2px; margin: 0; font-weight: normal;
                           padding-top: 20px; padding-bottom: 30px;">Mail us at <a
                           style="text-decoration: none; color: #ffffff; font-size: 16px; padding: 0;
                           letter-spacing: 1px; margin: 0; font-weight: normal;"
                           href=""></a></p>
                        <table height="" align="center" border="0" cellspacing="0" cellpadding="0"
                           style="border: 1px solid #515151; width: 100%;">
                        <tbody>
                        <tr>
                        <td style="text-align: center; border-right: 1px solid #515151;
                           padding-top: 10px; padding-bottom: 10px; width: 50%;"><a
                           href=""
                           style="font-family: 'Open Sans', sans-serif; text-decoration: none; color:
                           #ffffff; font-size: 14px; padding: 0; letter-spacing: 1px; margin: 0;
                           font-weight: normal;">UNSUBSCRIBE</a></td>
                        <td style="text-align: center; padding-top: 10px; padding-bottom: 10px;
                           border-right: 1px solid #515151;"><a
                           href=""
                           style="font-family: 'Open Sans', sans-serif; text-decoration: none; color:
                           #ffffff; font-size: 14px; padding: 0; letter-spacing: 1px; margin: 0;
                           font-weight: normal;">PRIVACY POLICY</a></td>
                        </tr>
                        </tbody>
                        </table>
                        <p style="color: #646464; font-family: 'Open Sans', sans-serif; font-size:
                           14px; padding: 0; margin: 0; font-weight: normal; padding-top: 25px;
                           padding-bottom: 15px;">&copy; 2020 thecrm360. All rights reserved.</p>
                        </td>
                        </tr> -->
                        <!-- end@row Footer -->
                     </tbody>
                  </table>
                  <!-- End Email Container -->
               </td>
            </tr>
         </tbody>
      </table>
      <br/> 
   </body>
</html>