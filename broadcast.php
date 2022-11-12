<?php
include_once("json_rpc.php");

if ($_POST[rawtx]) {
  $rawtx = $_POST[rawtx];
  if (ctype_xdigit($rawtx)) {
    $rpc = new jsonRPCClient();
    $txid = $rpc->sendrawtransaction($rawtx);
    if ($txid) {
      print '<a href="https://explorer.mewccrypto.com/tx/'.$txid.'" target="_txid">View transaction</a>';
    }
  }
}
