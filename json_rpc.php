<?php
class jsonRPCClient {
  
  private $url;
  private $id;
  private $notification = false;
  public function __construct() {
    # 8751 is the block explorer / meowcoincore running locally
    $url = "http://meowcoin:MEOW_Pass01@localhost:3545/"; 
    $this->url = $url;
    $this->id = 1;
  }
  /**
   * Sets the notification state of the object. In this state, notifications are performed, instead of requests.
   *
   * @param boolean $notification
   */
  public function setRPCNotification($notification) {
    empty($notification) ?
              $this->notification = false
              :
              $this->notification = true;
  }
  
  /**
   * Performs a jsonRCP request and gets the results as an array
   *
   * @param string $method
   * @param array $params
   * @return array
   */
  public function __call($method,$params) {
    
    // check
    if (!is_scalar($method)) {
      throw new Exception('Method name has no scalar value');
    }
    
    // check
    if (is_array($params)) {
      // no keys
      $params = array_values($params);
    } else {
      throw new Exception('Params must be given as array');
    }
    
    // sets notification or request task
    if ($this->notification) {
      $currentId = NULL;
    } else {
      $currentId = $this->id;
    }
    
    // prepares the request
    $request = array(
            'method' => $method,
            'params' => $params,
            'id' => $currentId
            );
    $request = json_encode($request);
    // echo "<br>".$request."<br>";
    $logmsg .= microtime() . '***** Request *****'."\n".$request."\n".'***** End Of request *****'."\n\n";
    
    // performs the HTTP POST
    $opts = array ('http' => array (
              'method'  => 'POST',
              'header'  => 'Content-type: application/json',
              'content' => $request
              ));
    $context  = stream_context_create($opts);
    $logmsg .= microtime();
    file_put_contents('/tmp/wallet.log',"$logmsg\n", FILE_APPEND);

    if ($fp = fopen($this->url, 'r', false, $context)) {
      $response = '';
      while($row = fgets($fp)) {
        $response.= trim($row)."\n";
      }
      $logmsg .= microtime() . '***** Server response *****'."\n".$response.'***** End of server response *****'."\n";
      file_put_contents('/tmp/wallet.log',"$logmsg\n", FILE_APPEND);
      $response = json_decode($response,true);
    } else {
      $logmsg .= microtime() . 'Unable to connect...'."\n";
      file_put_contents('/tmp/wallet.log',"$logmsg\n", FILE_APPEND);
      throw new Exception('Unable to connect to '.$this->url);
    }
    
    
    // final checks and return
    if (!$this->notification) {
      // check
      if ($response['id'] != $currentId) {
        throw new Exception('Incorrect response id (request id: '.$currentId.', response id: '.$response['id'].')');
      }
      if (!is_null($response['error'])) {
        throw new Exception('Request error: '.$response['error']);
      }
      
      return $response['result'];
      
    } else {
      return true;
    }
  }
}
?>
