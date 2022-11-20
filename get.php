<?php

$addy = $_GET[addy];
if ($addy) {
  $url = "https://explorer.mewccrypto.com/ext/getbalance/$addy";

  $handle = curl_init();
  curl_setopt_array($handle,
    array(
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true
    )
  );
  
  $file = curl_exec($handle);
  curl_close($handle);

  //if ($file) { $file = $file / 100000000; }
  print $file;
}
