<?php
  foreach ($docTemplate->result() as $key => $value) {
    $content=  $value->content;
   
    foreach ($enquiry as $key => $evalue) {

      $content = str_replace("@{fullname}",$evalue->name, $content);
      $content = str_replace("@{mobile}",$evalue->phone, $content);
      $content = str_replace("@{email}",$evalue->email, $content);
      $content = str_replace("@{address}",$evalue->address, $content);
      $content = str_replace("@{creationdate}",$evalue->created_date, $content);
    }
//user
  //  $content = str_replace("@{designation}",$evalue->, $content);
   $content = str_replace("@{username}",$usrarr->s_display_name.' '.$usrarr->last_name, $content);
   $content = str_replace("@{usermobile}",$usrarr->s_phoneno, $content);
   $content = str_replace("@{useremail}",$usrarr->s_user_email, $content);
   $content = str_replace("@{userdesignation}",$usrarr->designation, $content);
   
   if(!empty($freight_table))
    $content = str_replace('@freight_table', $freight_table,$content);
  if(!empty($oc_table))
   $content = str_replace('@oc_table', $oc_table,$content);

                            echo $content;

                                }



?>