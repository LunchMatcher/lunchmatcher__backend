<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Cron extends CI_Controller {

		public $match_array=array();
		public $user_list = array();
		public $no_of_match = 0;
		public function __construct()
		{                        
			parent::__construct();
			$this->load->model('cron_model');
			$this->load->model('user_model');
		}
			
		 
		public function index()
		{
			
		}
		
		public function arePointsNear($checkPoint, $centerPoint, $km) {
			$ky = 40000 / 360;
			$kx = cos(pi() * $centerPoint['lat'] / 180.0) * $ky;
			$dx = abs($centerPoint['lng'] - $checkPoint['lng']) * $kx;
			$dy = abs($centerPoint['lat'] - $checkPoint['lat']) * $ky;
			return sqrt($dx * $dx + $dy * $dy) <= $km;
		}
		public function getTodaysMatchUsers($date='')
		{
			$users_list=$this->cron_model->getTodaysMatchUsers($date);
			return $users_list;
		}
		public function checkLog($logid='',$logid_match='')
		{
			
			$flag=0;
			$match_list		=	$this->match_array;
			
			$log_array		= $match_list[$logid];
			if(empty($log_array)){
			return false;
			}
			
			if(in_array($logid_match,$log_array)){
					
					return true;
				}
			else{ 
					return false;
				}
		
		}
		public function createGroup($results,$group_match,$id) //recursive function
		{
			$i=0;
			if(!empty($results)){
				foreach($results as $key=>$result){
				
					$companies_exclude_match_a=array();
					$companies_exclude_match_b=array();
					$check_match=0;
					
					if($i==0){
						$i=1;
						$group_match[$id][]	=	$result;
						$fromtime 	=	date('H:i',strtotime($result['match_time_from']));
						$totime   	=	date('H:i',strtotime($result['match_time_to']));
						$venues		=	explode(',',$result['venues']);
						$logid		= 	$result['match_logid'];
						$gender		= $result['gender'];
						$gender_exclude	=	$result['gender_exclude'];
						$exclude_pre_match = $result['exclude_pre_match'];
						$member_id	=	$result['member_id'];
						$companies	=	explode(',',$result['companies']);
						$excluded_companies	=	explode(',',$result['excluded_companies']);
						unset($results[$key]);
					}
					else
					{
						$fromtime_match 			  =	date('H:i',strtotime($result['match_time_from']));
						$totime_match  				=	date('H:i',strtotime($result['match_time_to']));
						$venues_match				=	explode(',',$result['venues']);
						$logid_match				 = 	$result['match_logid'];
						$gender_match				=    $result['gender'];
						$gender_exclude_match		=	$result['gender_exclude'];
						$match_exclude_pre_match 	 =    $result['exclude_pre_match'];
						$member_id_match			 =	$result['member_id'];
						$companies_match			 =	$result['companies']?explode(',',$result['companies']):'';
						$excluded_companies_match	=	$result['excluded_companies']?explode(',',$result['excluded_companies']):'';
						//echo '<pre>';print_r($match_list);
						//if($fromtime_match == $fromtime) /// find match with same time  //&& ($totime_match == $totime)
						//{
							
							if( ($gender!=$gender_exclude_match) && ($gender_exclude!=$gender_match) ){ //  find match without gender exclusion
								
								if($exclude_pre_match=='Y' || $match_exclude_pre_match=='Y')
									$check_match = $this->cron_model->checkPreMatch($member_id,$member_id_match); //  find match without pre_match
								
									if($check_match==0){
									
											if(!empty($excluded_companies))
												$companies_exclude_match_a = array_intersect($excluded_companies, $companies_match); // if A have exculded any companies of B
												
											if(!empty($excluded_companies))
												$companies_exclude_match_b = array_intersect($excluded_companies_match, $companies); // if B have exculded any companies of A
												
												if(empty($companies_exclude_match_a) && empty($companies_exclude_match_b)){
												
													if($venues[0]!=''){
														$venue_match = array_intersect($venues, $venues_match); //matches with same resturant
														
														$check_log	 = $this->checkLog($logid,$logid_match); //checking on temp_matching_log
														
														if(!empty($venue_match) && !$check_log){ 
													
														//echo '<pre>'; print_r($check_log);echo '</pre>'; exit;
															$group_match[$id][]	=	$result;
															
															unset($results[$key]);
															break;
															}
													}
												}
									}
							}
							
						//}
					}
					

				
				}
				$id=$id+1;
				return $this->createGroup($results,$group_match,$id);
			
				}
				else
				{
				
				return $group_match;
				}
		}
		public function insertTempLog($group_array)
		{
			$noti_array	=	array();	
			$user_list	=	$this->user_list;
			$no_of_match = 0;
			$notmatch 	= array();
			foreach($group_array as $group)
			{
				if(count($group)!=2){
					$notmatch[] = $group[0];
					continue;
				}
				
				$no_of_match ++;
				//from user//
				$from_match_logid	= $group[0]['match_logid'];
				$from_member_id		= $group[0]['member_id'];
				
				//to user//
				$to_match_logid		= $group[1]['match_logid'];
				$to_member_id		= $group[1]['member_id'];
				
				$date				= date('Y-m-d H:i:s');
				$insert_data= array(
									'from_match_logid'=>$from_match_logid,
									'to_match_logid'=>$to_match_logid,
									'match_date'=>$date,
									'created_time'=>$date);
									
				$temp_logid			= $this->cron_model->insertTempmatch($insert_data);
				
				if($temp_logid)
				{
					//insertPoint(array('id' => 2, 'member_id' => $from_member_id),$to_member_id);
					//insertPoint(array('id' => 2, 'member_id' => $to_member_id),$from_member_id);
						
					$from_array			= array('for_match_logid'=>$to_match_logid,'member_id'=>$from_member_id,'temp_logid'=>$temp_logid);
					$to_array			= array('for_match_logid'=>$from_match_logid,'member_id'=>$to_member_id,'temp_logid'=>$temp_logid);
					$noti_array[]		= $from_array;
					$noti_array[]		= $to_array;
					$user_list[]		= $from_member_id;
					$user_list[]		= $to_member_id;
				}
				
			}
			$this->user_list	=	$user_list;
			$this->no_of_match	=	$no_of_match;
			$result['noti'] = $noti_array;
			$result['no_noti'] = $notmatch;
			
			return $result;
		}
		public function sample_notification(){ 
			$user = (object) array(
				'device_platform' => 'Android',
				'gcm_reg_id' => 'APA91bFvm_eQmP3ctvrEotWZluCdw5IzJ14s7zlkZPVGuI6tEe4Nt3beSDyWIckNZ36u6bSUnfcop28T19FYreBHhMk4u0LuFYr0jmPpD0x4R1C_BxcoDgQ6pzOnLVX-gVIbYXhHDZD1'			
			);	
			$payload = array(
				'title' => "Sample notification",
				'message'=> 'Sample text for notification.',
				'anchor' => '/app/scorepoints',
			);	
			$this->_initNotifications($user,$payload);
		}		
		public function sample_notification_IOS(){ 
			$this->load->model('general_model'); 
		
			$payload = array(
				'title' => "Sample notification",
				'message'=> 'Sample text for notification.',
				'anchor' => '/app/scorepoints',
			);
			$user = (object) array(
				'device_platform' => 'iOS',
				'ios_device_token' => 'eb21d04cd8012034891b7d7beb50e00142b4eb540c3c7bc7154662e3c2538f9b'		
			);
			//'968fd22124410c15e0cfeae43abc47c0ed3af594675e28fbb611f93388f413b6'	
			//$this->_initNotifications($user,$payload);	
			$res = $this->general_model->sendPushNotification_IOS($payload['title'],$user->ios_device_token,$payload);
			
			if($res)
				echo "Notification sent!"; 
			else 
				echo "Notification not sent!"; 
		
			exit;
		}
		
		public function sendMatchPushNotification($noti_array){
			
			$member_id	= $noti_array['member_id'];
			$temp_logid	= $noti_array['temp_logid'];
			$match_id	= $noti_array['for_match_logid'];
			$user_data	= $this->cron_model->getUser(array('member_id'=>$member_id));
			$device_id	= $user_data->device_token;
			if($device_id){
			$user = (object) array(
				'device_platform' => $user_data->device_platform,
				'gcm_reg_id' => $device_id			
			);

			$payload = array(
				'title' => "Today's match",
				'message'=> 'We find a match for you',
				'anchor' => '/app/matchprofile/'.$temp_logid.'/'.$match_id,
			);
				echo '<pre>Push user:';print_r($user);
				echo '<pre>Push data:';print_r($payload);
			$this->_initNotifications($user,$payload);
			}
		
		}
		
	public function sendNoMatchPushNotification($noti_array,$crone_time){
			
			$to_time 		= strtotime($noti_array['last_notific']);
			$from_time 	  = strtotime($crone_time);
			$time_diff 	  = round(abs($to_time - $from_time) / 60,2);
			$logid_match 	= $noti_array['match_logid'];
			$notif	      = $this->cron_model->checkTempLog($logid_match);
			
			if($time_diff  <= 15  && $notif == 0){
				
				$user_data	= $this->cron_model->getUser(array('member_id'=>$noti_array['member_id']));
				
				$arr['member_id'] 	 = $noti_array['member_id'];
				$arr['match_logid']   = $noti_array['match_logid'];
				$arr['match_date'] 	= date('Y-m-d'); 
				$arr['created_time']  = date('Y-m-d H:i:s');
				$nomatch	= $this->cron_model->insertNoMatch($arr);
	
				$device_id	= $user_data->device_token;
				if($device_id){
				$user = (object) array(
					'device_platform' => $user_data->device_platform,
					'gcm_reg_id' => $device_id			
					);
	
				$payload = array(
					'title' => $this->config->item('NoMatchPushNotificationTitle'),
					'message'=> $this->config->item('NoMatchPushNotificationMessage'),
					'anchor' => '/app/availability',
				);
					echo '<pre>Push user:';print_r($user);
					echo '<pre>Push data:';print_r($payload);
					$this->_initNotifications($user,$payload);
				}
			}
		
		}
		
		public function sendEndingTimePushNotification($noti_array,$who=''){
		
			$user_id	= $noti_array['user_id'];
			$logid		= $noti_array['logid'];
			$to_user_id	= $noti_array['to_user_id'];
			$user_data	= $this->cron_model->getUser(array('member_id'=>$user_id));
			$device_id	= $user_data->device_token;
			
			if($device_id){
			$user = (object) array(
				'device_platform' => $user_data->device_platform,
				'gcm_reg_id' => $device_id			
				);

			$payload = array(
				'title' => "Review to Matching Profile ",
				'message'=> 'Put Rating & Review to your matcher',
				'anchor' => '/app/profilereview/'.$logid.'/'.$to_user_id,
			);
				echo '<pre>Push user:';print_r($user);
				echo '<pre>Push data:';print_r($payload);
				$this->_initNotifications($user,$payload);
			}
			
			$user_data	= $this->cron_model->updateMatchSceduleExpire($logid,$who);
		}
	// protected function to send out notifications
		protected function _initNotifications($user,$payload){ 	
			$this->load->model('general_model'); 
			if($user->device_platform == 'Android' || $user->device_platform == 'android'){
				if($user->gcm_reg_id){  
					$reg_id = array(0=>$user->gcm_reg_id);				
					$this->general_model->sendPushNotification_ANDROID($reg_id,$payload);
				}
			}else{
				if($user->gcm_reg_id)		
					$this->general_model->sendPushNotification_IOS($payload['title'],$user->gcm_reg_id,$payload);		
			}
		
		}
		
		public function findMatchCron()
		{
			
				$cron_id = $this->cron_model->insert_cron_log('findMatchCron'); // for cron log
				$this->no_of_match=0;			
				$date=date('Y-m-d');
				$cron_start = $this->config->item('cron_start')?$this->config->item('cron_start'):2;
				if(date('i') >= 0 && date('i') <=14)
					$from_time  = date('Y-m-d H:00');
				if(date('i') >= 15 && date('i') <=29)
					$from_time  = date('Y-m-d H:15');
				if(date('i') >= 30 && date('i') <=44)
					$from_time  = date('Y-m-d H:30');
				if(date('i') >= 45 && date('i') <=59)
					$from_time  = date('Y-m-d H:45');
					
					
				$to_time 	= date('Y-m-d H:i', strtotime($from_time . ' +'.$cron_start.' hours'));
				
				#### For testing
				//$from_time   = '2015-08-06 12:00';
				//$date		= '2015-08-06';
				//$to_time 	 = '2015-06-24 12:00';
				
				$crone_time  = $from_time;
				
				$expUsers = $this->cron_model->getExpiredNotificUser($date,$from_time,$to_time);
				if($expUsers){
					foreach($expUsers as $expUser){
						$this->cron_model->update_temp_log(array('point_deducted'=>'Y'), $expUser['log_id']);
						$match_ownerid = $this->cron_model->getMatchOwnerid($expUser['log_id'],$expUser['member_id']);	
						insertPoint(array('id' => 4, 'member_id' => $expUser['member_id']), $match_ownerid);				
					}
				}
			
				$users_list=$this->cron_model->getAllLogUsers($date,$crone_time);
				
				//echo '<pre>'; print_r($users_list); echo '</pre>'; exit;
				$group_match=array();
				
				$match_list		 =	$this->getTodaysMatchUsers($date);
				$this->match_array  =	$match_list;
				$group_array	    =	$this->createGroup($users_list,$group_match,0);
				
				$expireLogs		 = 	$this->cron_model->updateTempLogExpire($from_time);
				$notificaton_array  =	$this->insertTempLog($group_array, $from_time);
				
				foreach($notificaton_array['noti'] as $notificaton){
				
					$this->sendMatchPushNotification($notificaton);
				
				}
				//echo '<pre>'; print_r($notificaton_array); echo '</pre>'; exit;
				foreach($notificaton_array['no_noti'] as $no_notificaton){
					
					$this->sendNoMatchPushNotification($no_notificaton, $crone_time);
				
				}
				
				echo '<pre>Group:';print_r($group_array);
				echo '<pre>Notification:';print_r($notificaton_array['noti']);
				echo '<pre>No Notification:';print_r($notificaton_array['no_noti']);
				
			
			
			//////////cron_log/////////
			$user_list_json = json_encode($users_list);
			$group_array_json = json_encode($group_array);
			
			//////////////////////////
			$this->write_log('findMatchCron',$cron_id,$user_list_json,$group_array_json);
		}
		public function sendReviewCron()
		{
		
			$cron_id = $this->cron_model->insert_cron_log('sendReviewCron'); // for cron log
			
			//if(date('H')>=6){
			
				$date= date('Y-m-d H:i');
				$group_match=array();
				$match_complete_logs	 =	$this->cron_model->getEndingUserList($date);
				
				$user_list	= $this->user_list;
				//echo '<pre>'; print_r($match_complete_logs); echo '</pre>'; exit;
				foreach($match_complete_logs as $log){
					
					$log_id			=	$log['log_id'];
					$from_log_id	=	$log['from_log_id'];
					$to_log_id		=	$log['to_log_id'];
					$from_user		=	$log['from_user'];
					$to_user		=	$log['to_user'];
					
					$noti_array_to		= array('user_id'=>$from_user,'logid'=>$log_id,'to_user_id'=>$to_user);
					$noti_array_from		= array('user_id'=>$to_user,'logid'=>$log_id,'to_user_id'=>$from_user);
					
					$this->sendEndingTimePushNotification($noti_array_to,'to_log_id');					
					$this->sendEndingTimePushNotification($noti_array_from,'from_log_id');
					
					
					$user_list[] = $from_user;
					$user_list[] = $to_user;
					
				}
				$this->user_list	= $user_list;
				
				$match_complete_json = json_encode($match_complete_logs);
				
				echo '<pre>Match Completed User Log:';print_r($match_complete_logs);
			//}
		
			$this->write_log('sendReviewCron',$cron_id,$match_complete_json);
		}
		
		public function write_log($function,$cron_id='',$user_array="",$group_array=""){
			
			//////////cron_log/////////
			$date_time    = date('Ymd');
			$date	     = date('Y-m-d H:i:s');
			$user_list	= $this->user_list;
			$yesterday    = date("Y-m-d", strtotime("yesterday"));
			$this->cron_model->deleteYesterdayLog($yesterday);
	
			//////////cron_log/////////
			if(!empty($user_list))
				$text = array();
				
			foreach($user_list as $user_id ){
				$user_data	= $this->cron_model->getUser(array('member_id'=>$user_id));
				$device_id	= $user_data->device_token;
				$text[] =  " User:".$user_id." :- ".$device_id;
			}
			
			if($function=='findMatchCron'){
			
				//////////cron_log/////////
				$no_of_match = $this->no_of_match;
				$insert_array= array('cron_id'=>$cron_id,'users_details_for_match'=>$user_array,'match_group'=>$group_array,'device_list'=>json_encode($text),'no_of_match'=>$no_of_match,'status'=>'Y') ;
				$this->cron_model->update_cron_log($insert_array);
				//////////cron_log/////////
				
				/*$file_name= APPPATH.'logs/cron/'.$date_time.'_findMatchCron.txt';
				$myfile = fopen($file_name, "a+");
				$txt = "Cron running on time : $date \r\n";
				$txt.=$text."\r\n";
				fwrite($myfile, $txt);				
				fclose($myfile);*/
			
			}
			else if($function=='sendReviewCron'){
								
				//////////cron_log/////////
				$insert_array= array('cron_id'=>$cron_id,'match_completed_user_log'=>$user_array,'device_list'=>json_encode($text),'status'=>'Y') ;
				$this->cron_model->update_cron_log($insert_array);
				//////////cron_log/////////
				
					/*$file_name= APPPATH.'logs/cron/'.$date_time.'_sendReviewCron.txt';
					$myfile = fopen($file_name, "a+");
					$txt = "Cron running on time : $date \n";
					$txt.=$text.' \n';
					fwrite($myfile, $txt);
					
					fclose($myfile);*/
			
			}
		
		
		}
		public function dailyReminder(){
			
			if(date('i') >= 0 && date('i') <=29)
				$time  = date('H:00:00');
			if(date('i') >= 30 && date('i') <=59)
				$time  = date('H:30:00');
			
			if(date("H") > 11 || (date("H") == 11 && date("m") >= 45)){
				$date = date("Y-m-d", strtotime("tomorrow"));
			}
			else{
				$date 	  = date('Y-m-d');	
			}
			
			$reminders =	$this->cron_model->getDailyReminderList($time,$date);
			//echo "<pre>"; print_r($reminders); echo "</pre>"; exit;
			if($reminders){
				foreach($reminders as $reminder){
					
					if($reminder->device_token){
						
						$user = (object) array(
							'device_platform' => $reminder->device_platform,
							'gcm_reg_id' => $reminder->device_token			
						);
						
						$payload = array(
							'title' => "Daily reminder",
							'message'=> 'Set your availabilty for next lunch',
							'anchor' => '/app/availability'
						);
						$this->_initNotifications($user,$payload);
					}
				}
			}
			echo "Cron run time : ".$time; exit;	
		}

		

		
	}

		