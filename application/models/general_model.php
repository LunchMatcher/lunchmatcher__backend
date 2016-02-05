<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class General_model extends MY_Model { 
	public $_table = '';
	public function setTable($table){
		$this->_table = $table;
	}
	function get_result_query($query){
		$result = $this->db->query($query);		
		return $result->result();
	}
	function getFieldNames($table){
		return $this->db->list_fields($table);
	
	}
	function _insertInto($table='',$data){
		$arr1 				= array_flip($this->getFieldNames($table));  		
		$data_master 		= array_intersect_key($data, $arr1);
		$this->db->set($data_master);
		$this->db->insert($table);		
		return $this->db->insert_id();
	}
	function updateTable($table,$cond='',$data=''){
		if($cond && $data){
			$this->db->where($cond);
			$this->db->set($data);	
			$this->db->update($table);
			return true;
		}
		return false;
	}
	
	function delete_with($table,$cond){
		$this->db->delete($table, $cond); 
		return true;
	}
	
	# function to send push notification IOS

   function sendPushNotification_IOS($message, $devicetocken,$additional_params = '') {		
			   // Put your device token here (without spaces):
			   $deviceToken = $devicetocken;
		//echo  $deviceToken; exit;
			   // Put your private key's passphrase here:
			   $passphrase = 'smb2win';
		
			   ////////////////////////////////////////////////////////////////////////////////
		
			   $ctx = stream_context_create();
			 //  stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns-dev.pem');
			   stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns-prod.pem');
			   stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		
			   // Open a connection to the APNS server
			   //gateway.push.apple.com
			   ################# use this for test server ###############################
			   //$fp = stream_socket_client(
				//	   'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
			   ################# use this for test server ###############################
			   ################# use this for live server ###############################
		       $fp = stream_socket_client(
		                'ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
			   ################# use this for live server ###############################
		
			   if (!$fp) {
		//            exit("Failed to connect: $err $errstr" . PHP_EOL);
				   return false;
			   }
		
			   //echo 'Connected to APNS' . PHP_EOL;
			   $badge_count = 1;
			   // Create the payload body
			   $body['aps'] = array(
				   'alert' => $message,
				   'badge' => intval($badge_count),
				   'sound' => 'default'
			   );
			   
			   if(is_array($additional_params)){
			   		$body = array_merge($body,$additional_params);
			   }
		
		//        $body['from_user'] = $from_user;
		//        $body['from_id'] = $from_id;
			   // Encode the payload as JSON
			   $payload = json_encode($body);
		
			   // Build the binary notification
			   $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		
			   // Send it to the server
			   $result = fwrite($fp, $msg, strlen($msg));
		//var_dump($result);
			//    print_r($body);
			
			
				 // Close the connection to the server
			   fclose($fp);	
				
			   if (!$result)
				   return false;
			   else
				   return true;
		
			 
		   }
		
		   # send push notification Android
		
public function sendPushNotification_ANDROID($registatoin_ids, $message) {
		//	echo"<pre>"; print_r( $registatoin_ids );echo"</pre>";
		//	echo"<pre>"; print_r($message); echo"</pre>";exit;
			   // include config
			   // Set POST variables
			   $url = 'https://android.googleapis.com/gcm/send';
		
			   $fields = array(
				   'registration_ids' => $registatoin_ids,
				   'data' => $message,
			   );
		
			  /* $headers = array(
				   'Authorization: key=AIzaSyAaE9oXscjbYRPjUla2oAqdxS7Cuk63vuc',
				   'Content-Type: application/json'
			   );*/
			   $headers = array(
				   'Authorization: key=AIzaSyBq6JqQbFI3vSLrcCAY4dzvYeRhvCCp4J0',
				   'Content-Type: application/json'
			   );
			   
			   // Open connection
			   $ch = curl_init();
		
			   // Set the url, number of POST vars, POST data
			   curl_setopt($ch, CURLOPT_URL, $url);
		
			   curl_setopt($ch, CURLOPT_POST, true);
			   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
			   // Disabling SSL Certificate support temporarly
			   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
			   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		
			   // Execute post
			   $result = curl_exec($ch);
			   if ($result === FALSE) {
				   die('Curl failed: ' . curl_error($ch));
			   }
		
			   // Close connection
			   curl_close($ch);
			  return $result;
			   //print_r($fields);
			     echo $result;
		   }
	
	 
}