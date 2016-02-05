<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Notifications
 * @category	Controller
 * @author		Robin A Thomas
*/
 
class Notification extends CI_Controller
{
	public function __construct(){  
		parent::__construct();	
	}
	function sample_notification(){ 
		$user = (object) array(
			'device_platform' => 'Android',
			'gcm_reg_id' => 'APA91bFLWW5o1g399Z_DE48jddm8xjcettqsDJY0FGuUbP9bfqHsicASqoQb2hmMRqm4d6JKzhn-brebsQa0jQHmaIK4846pLvm7JXf-l21rz4nYWDriCVlVgLk3d7HSeDqJgpHhngZMJrBPii1clF8T44C4EgzpQOOwN86Gr3i-n8Bphfg-1jMPwDSD74F1tv13MVKYb_2f'			
		);	
		$payload = array(
			'title' => 'Sample notification',
			'message'=> 'Sample text for notification.',
			'anchor' => '/app/matchprofile/26/532',
		);	
		$this->_initNotifications($user,$payload);
	}
	function test_notification(){
		file_put_contents('test_notif.txt','Content for the notification test');
	}
	public function test($id){
		$this->load->model('user_model');
		 $user = $this->user_model->getUserDetails($id);	
		 $this->load->model('general_model');	
		if($user->device_platform == 'Android' || $user->device_platform == 'android'){
		var_dump($user);
			if($user->device_token){
				$reg_id = array(0=>$user->device_token);
				
				$this->general_model->sendPushNotification_ANDROID($reg_id,array('title' => 'Sample notification',
				'message'=> 'Sample notification',
				'anchor' => '/app/matchprofile/26/533'));
			}
		}else if($user->device_token){ 
			$this->general_model->sendPushNotification_IOS('Hello '.$user->name,$user->device_token);		
		}
		/*$url = 'https://graph.facebook.com/1490728057851474/picture?type=large';
		 
		$data = file_get_contents($url);
		$fileName = FCPATH.'fb_profilepic.jpg';
		$file = fopen($fileName, 'w+');
		fputs($file, $data);
		fclose($file);
		
		echo '<img src="'.base_url().'fb_profilepic.jpg" />';*/
		/*$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ignore SSL verifying
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$response = curl_exec($ch);
		curl_close($ch);			 
		var_dump($response);*/
		 
	}
	// protected function to send out notifications
	protected function _initNotifications($user,$payload){ 	
		$this->load->model('general_model'); 
		if($user->device_platform == 'Android' || $user->device_platform == 'android'){
			if($user->gcm_reg_id){  
				$reg_id = array(0=>$user->gcm_reg_id);				
				$this->general_model->sendPushNotification_ANDROID($reg_id,$payload);
			}
		}else if($user->ios_device_token){ 			
			$this->general_model->sendPushNotification_IOS($payload['title'],$user->ios_device_token,$payload);		
		}
	
	}
}
 