<?php
  $sig_expires_in = 20; // value in minutes
  $max_record_time = 60; // value in seconds
  $session_data = array();

  // ONLY EDIT BELOW THIS LINE IF YOU ARE A 133t H@x0R

  $timestamp = time() + ($sig_expires_in * 60);
  $signature = md5($api_secret . "&" . $timestamp);
  $session_data_string = '';
  foreach ($session_data as $key => $value) {
    $session_data_string .= ',' . $key . '=' . $value;
  }
  $divid = "frameyRecorderContainer_" . 1;
  $objid = "the". $divid;
?>