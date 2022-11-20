<?php

$addy = $_GET[addy];
$url = "https://explorer.mewccrypto.com/ext/getlasttxsajax/$addy";

$handle = curl_init();
curl_setopt_array($handle,
  array(
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_URL            => $url,
      CURLOPT_RETURNTRANSFER => true
  )
);

$output = curl_exec($handle);
curl_close($handle);
print $output;
