<!DOCTYPE HTML PUBLIC "-/W3C/DTD HTML 4.01/EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <title></title>
    <style type="text/css">
    </style>
</head>

<body style="margin:0; padding:0;">
    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#edeff1" style="margin: 0px; padding-top: 40px; padding-bottom: 80px; width:100%;">
        <tbody>
            <tr>
                <td>
                    <table class="container" align="center" border="0" cellspacing="0" cellpadding="0"
                        style="width: 640px;">
                        
                        <tbody>
                            <tr>
                                <td valign="top" style="text-align: center; padding: 20px 50px;background-color: #ffffff;">
                                    <!-- Intro content -->
                                    <p style="font-family: 'Open Sans', sans-serif; color: #333333; font-size:19px; font-weight: 500; width: 90%; margin: 0 auto;"><img
                                            src="https://v-trans.thecrm360.com/assets/images/V-Trans_NewLogo.png" alt=""
                                            style="height: 100px; width: auto;" /></p>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" style="text-align: left; padding: 20px 50px;background-color: #ffffff;">
                                    <!-- Intro content -->
                                    <p style="font-family: 'Open Sans', sans-serif; color: #333333; font-size:17px; font-weight: 500; margin: 0 auto;"> Dear <?=$userName?>, </p>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" style="text-align: center; padding: 20px 50px;background-color: #ffffff;">
                                    <!-- Intro content -->
                                    <p style="font-family: 'Open Sans', sans-serif; color: #333333; font-size:17px; font-weight: 500; margin: 0 auto; text-align: left;">
                                        Greetings for the day.
                                        <br>
                                        <br>
                                        Please refer to the below link to check for daily report of V-Trans CRM dated
                                        <b><?php echo date('Y-m-d',strtotime('-1 days')); ?></b>.<br><br>
                                    <div class="row row text-center short_dashboard" id="active_class">

                                        <section id="statistic" class="statistic-section one-page-section" style=" padding-top: 70px;padding-bottom: 70px;">
                                            <div>
                                                <div style="display: flex; -ms-flex-wrap: wrap; flex-wrap: wrap;  text-align: center!important;">
                                                    <div
                                                        style="flex: 0 0 140px;max-width: 140px;width: 140px; border:solid 1px black;-webkit-box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); margin:5px;background-color:  linear-gradient(to right, #1a75ff , white );">
                                                        <div class="counter">
                                                            <i class="fa fa-laptop fa-2x stats-icon"></i>
                                                            <h2 class="timer count-title count-number">Visit</h2>
                                                            <div class="stats-line-black"></div>
                                                            <p class="stats-text"><?= $get_today_call ?></p>
                                                        </div>
                                                    </div>
                                                    <div
                                                        style="flex: 0 0 140px;max-width: 140px;width: 140px; border:solid 1px black;-webkit-box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); margin:5px;background-color:  linear-gradient(to right, #1a75ff , white );">
                                                        <div class="counter">
                                                            <i class="fa fa-laptop fa-2x stats-icon"></i>
                                                            <h2 class="timer count-title count-number">Lead</h2>
                                                            <div class="stats-line-black"></div>
                                                            <p class="stats-text"><?= $get_today_nad ?></p>
                                                        </div>
                                                    </div>
                                                    <div
                                                        style="flex: 0 0 140px;max-width: 140px;width: 140px; border:solid 1px black;-webkit-box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); margin:5px;background-color:  linear-gradient(to right, #1a75ff , white );">
                                                        <div class="counter">
                                                            <i class="fa fa-laptop fa-2x stats-icon"></i>
                                                            <h2 class="timer count-title count-number">Approach</h2>
                                                            <div class="stats-line-black"></div>
                                                            <p class="stats-text"><?= $get_today_prospect ?></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div style="display: flex; -ms-flex-wrap: wrap; flex-wrap: wrap;  text-align: center!important;">
                                                    <div style="flex: 0 0 140px;max-width: 140px;width: 140px; border:solid 1px black;-webkit-box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); margin:5px;background-color:  linear-gradient(to right, #1a75ff , white );">
                                                        <div class="counter">
                                                            <i class="fa fa-laptop fa-2x stats-icon"></i>
                                                            <h2 class="timer count-title count-number">Negotiation</h2>
                                                            <div class="stats-line-black"></div>
                                                            <p class="stats-text"><?= $get_today_approach ?></p>
                                                        </div>
                                                    </div>

                                                    <div
                                                        style="flex: 0 0 140px;max-width: 140px;width: 140px; border:solid 1px black;-webkit-box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); margin:5px;background-color:  linear-gradient(to right, #1a75ff , white );">
                                                        <div class="counter">
                                                            <i class="fa fa-laptop fa-2x stats-icon"></i>
                                                            <h2 class="timer count-title count-number">Closure</h2>
                                                            <div class="stats-line-black"></div>
                                                            <p class="stats-text"><?= $get_today_negociation ?></p>
                                                        </div>
                                                    </div>
                                                    <div
                                                        style="flex: 0 0 140px;max-width: 140px;width: 140px; border:solid 1px black;-webkit-box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); margin:5px;background-color:  linear-gradient(to right, #1a75ff , white );">
                                                        <div class="counter">
                                                            <i class="fa fa-laptop fa-2x stats-icon"></i>
                                                            <h2 class="timer count-title count-number">Order</h2>
                                                            <div class="stats-line-black"></div>
                                                            <p class="stats-text"><?= $get_today_fo ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="display: flex; -ms-flex-wrap: wrap; flex-wrap: wrap;  text-align: center!important;">
                                                    <div
                                                        style="flex: 0 0 140px;max-width: 140px;width: 140px; border:solid 1px black;-webkit-box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); box-shadow: 4px 3px 13px 4px rgba(0,0,0,0.48); margin:5px;background-color:  linear-gradient(to right, #1a75ff , white );">
                                                        <div class="counter">
                                                            <i class="fa fa-laptop fa-2x stats-icon"></i>
                                                            <h2 class="timer count-title count-number">Future
                                                                opportunities</h2>
                                                            <div class="stats-line-black"></div>
                                                            <p class="stats-text"><?= $get_today_order ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                    <br>
                                    <a href="<?= $links ?>" target="_BLANK"><button type="button" style="font-size: 18px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; font-weight: bold; text-decoration: none; border-radius: 5px; background-color: #0094de; border-top: 12px solid #0094de; border-bottom: 12px solid #0094de; border-right: 18px solid #0094de; border-left: 18px solid #0094de; display: inline-block;">Click Here to view &rarr;</button><br>
                                        <br>
                                        </p>
                                </td>
                            </tr>
                            <tr>    
                            
                        </tbody>
                    </table>
                    <!-- End Email Container -->
                </td>
            </tr>
        </tbody>
    </table>
    <br />
</body>
</html>