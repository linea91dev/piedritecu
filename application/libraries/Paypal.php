<?php

class Paypal {
    
   var $last_error;                 // holds the last error encountered
   var $ipn_log;                    // bool: log IPN results to text file?
   var $ipn_log_file;               // filename of the IPN log
   var $ipn_response;               // holds the IPN response from paypal   
   var $ipn_data = array();         // array contains the POST values for IPN
   var $fields = array();           // array holds the fields to submit to paypal

   function Paypal() 
   {
      $this->last_error = '';  
      $this->ipn_log_file = '.ipn_results.log';
      $this->ipn_log = true; 
      $this->ipn_response = '';
      $this->add_field('rm','2');           // Return method = POST
      $this->add_field('cmd','_xclick'); 
   }
   
   function add_field($field, $value) {
      $this->fields["$field"] = $value;
   }

   function submit_paypal_post() {
      echo "<html>\n";
      echo "<body onLoad=\"document.forms['paypal_form'].submit();\">\n";
      echo "<form method=\"post\" name=\"paypal_form\" ";
      echo "action=\"".$this->paypal_url."\">\n";

      foreach ($this->fields as $name => $value) {
         echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
      }
        
      echo "</form>\n";
      echo "</body></html>\n";
   }
   
   function validate_ipn() {      
      $raw_post_data = file_get_contents('php://input');
      $raw_post_array = explode('&', $raw_post_data);
      $myPost = array();
      foreach ($raw_post_array as $keyval) 
      {
        $keyval = explode ('=', $keyval);
        if (count($keyval) == 2)
          $myPost[$keyval[0]] = urldecode($keyval[1]);
      }
      $req = 'cmd=_notify-validate';
      if(function_exists('get_magic_quotes_gpc')) 
      {
         $get_magic_quotes_exists = true;
      } 
      foreach ($myPost as $key => $value) 
      {        
         if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) 
         { 
            $value = urlencode(stripslashes($value)); 
         } 
         else 
         {
            $value = urlencode($value);
         }
         $req .= "&$key=$value";
      }
       
      $ch = curl_init($this->paypal_url);
      curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
      curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
   
      if( !($res = curl_exec($ch)) ) {
         curl_close($ch);
         $this->write_log('curl error');
         exit;
      }
      curl_close($ch);
       
      if (strcmp ($res, "VERIFIED") == 0) 
      {
         return true;
         # assign posted variables to local variables
         #$uniqid          = $_POST['custom'];
         #$item_number     = $_POST['item_number'];
         #$payment_status  = $_POST['payment_status'];
         #$payment_amount  = $_POST['mc_gross'];
         #$payment_currency   = $_POST['mc_currency'];
         #$txn_id          = $_POST['txn_id'];
         #$txn_type        = $_POST['txn_type'];
         #$receiver_email  = $_POST['receiver_email'];
         #$payer_email     = $_POST['payer_email'];
         # check whether the payment_status is Completed
         # check that txn_id has not been previously processed
         # check that receiver_email is your Primary PayPal email
         # check that payment_amount/payment_currency are correct
      }
      else if (strcmp ($res, "INVALID") == 0) 
      { 
         return false;
      }
   }
   
   function log_ipn_results($success) {
       
      if (!$this->ipn_log) return;  // is logging turned off?
      
      // Timestamp
      $text = '['.date('m/d/Y g:i A').'] - '; 
      
      // Success or failure being logged?
      if ($success) $text .= "SUCCESS!\n";
      else $text .= 'FAIL: '.$this->last_error."\n";
      
      // Log the POST variables
      $text .= "IPN POST Vars from Paypal:\n";
      foreach ($this->ipn_data as $key=>$value) {
         $text .= "$key=$value, ";
      }
 
      // Log the response from the paypal server
      $text .= "\nIPN Response from Paypal Server:\n ".$this->ipn_response;
      
      // Write to log
      $fp=fopen($this->ipn_log_file,'a');
      fwrite($fp, $text . "\n\n"); 

      fclose($fp);  // close file
   }

   function dump_fields() {
 
      // Used for debugging, this function will output all the field/value pairs
      // that are currently defined in the instance of the class using the
      // add_field() function.
      
      echo "<h3>Paypal->dump_fields() Output:</h3>";
      echo "<table width=\"95%\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\">
            <tr>
               <td bgcolor=\"black\"><b><font color=\"white\">Field Name</font></b></td>
               <td bgcolor=\"black\"><b><font color=\"white\">Value</font></b></td>
            </tr>"; 
      
      ksort($this->fields);
      foreach ($this->fields as $key => $value) {
         echo "<tr><td>$key</td><td>".urldecode($value)."&nbsp;</td></tr>";
      }
 
      echo "</table><br>"; 
   }
}         