<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() 
    {       
         parent:: __construct();
         $this->load->model('mobile/user_model');	
		 $this->load->model('mobile/availability_model');
    }
	public function index()
	{
		$this->load->view('welcome_message');
	}
	function gettime(){
		$date=date('Y-m-d H:i');
		echo $date;
	}
	function getMatchHistory(){
		$postDataJSON 			= file_get_contents("php://input");
		$postData				= json_decode($postDataJSON, true);
		$member_id				= $postData['memberId'];
		$response				= array();	
		if($result =  $this->user_model->getHistory($member_id))
		{
			$response = array('status' => 'ok', 'message' => 'Date Fetched', 'result' => $result);
		}
		else
		{
			$response = array('status' => 'error', 'message' => 'Database Error', 'result' => $result);
		}
		echo json_encode($response);
	}
	function imageURL(){
		$this->load->model('mobile/venue_model');
			
		$postDataJSON 			= file_get_contents("php://input");
		$postData				= json_decode($postDataJSON, true);
		$venue_id				= $postData['venue_id'];
		
		if(!$venue_id)
			echo site_url("assets/images/small_no_image.png");
			
		$venue = $this->venue_model->getVenuDet($venue_id);
		//echo "<pre>"; print_r($venue); exit;	
		if($venue && $venue->image != '' && file_exists(FCPATH.'uploads/venue/'.$venue->image)){
			echo 	site_url('uploads/venue/'.$venue->image);
		}
		elseif($venue){
			
			echo 'https://maps.googleapis.com/maps/api/place/photo?maxheight=90&photoreference='.$venue->photo_key;	
		}
		else{
			echo null;	
		}
		
		exit;
	}
	function getPoints(){
		$postDataJSON 			= file_get_contents("php://input");
		$postData				= json_decode($postDataJSON, true);
		$member_id				= $postData['memberId'];
		$response				= array();
		$total_point 			=  $this->user_model->getTotalPoints($member_id);	
		if($result =  $this->user_model->getPoints($member_id))
		{
			$response = array('status' => 'ok', 'message' => 'Date Fetched', 'result' => $result,'total_point'=>$total_point);
		}
		else
		{
			$response = array('status' => 'error', 'message' => 'Database Error', 'result' => $resultm,'total_point'=>$total_point);
		}
		echo json_encode($response); exit();
	}
	function purchasePoint(){
		$pointJSON 		= file_get_contents("php://input");
		$pointData 		= json_decode($pointJSON, true);						
		$pointData		= $pointData['pointData'];
		
		$arr['member_id']  = $pointData['memberId'];
		$arr['point_id']   = 10;
		$arr['ref_id']  	 = 1;
		$arr['points']  	 = $pointData['point'];
		$arr['created_date'] = date("Y-m-d H:i:s");
		$arr['action_date'] = date("Y-m-d");
				
		$result = $this->user_model->purchasePoint($arr);
		//echo "<pre>"; print_r($result ); echo "</pre>"; exit;
		$response = array('status' => 'ok', 'message' => $this->config->item('pointPurchaseSuccess'));
		echo json_encode($response); exit();
	}
	
	function sendFeedback(){
		$reviewDetailJSON 	 = file_get_contents("php://input");
		$reviewInfo 		   = json_decode($reviewDetailJSON, true);
		$data				 = array();
		$insertData		   = $reviewInfo['feed'];
		$member_id   			= $reviewInfo['member_id'];
		$feedback 			 = $insertData['feed_textarea'];
		
		$user = $this->user_model->getUserDetails($member_id);
		if($user){
				$this->load->library('email');
				$this->email->from($user->email, $user->first_name);
				$admin_email = $this->config->item('admin_email')?$this->config->item('admin_email'):'info@lunchmatcher.com';
				$this->email->to($admin_email); 
				$this->email->subject('One Feedback from '.ucfirst($user->first_name));
				$this->email->message($feedback);	
				
				$send = $this->email->send();
				
				if($send){
					$response = array('status' => 'success', 'message' => 'Your feedback has been sent.','result' =>$user);
				}
				else{
					$response = array('status' => 'fail', 'message' => 'Error: Your feedback not send. Please try later.');
				}
		}
		else{
			$response = array('status' => 'fail', 'message' => 'Error: Your feedback not send. Please try later.');
		}
		
		
		
		echo json_encode($response);	 exit();			
	}
	function saveReview(){
		$this->load->model('mobile/venue_model');
		$this->load->model('mobile/feedback_model');
		$reviewDetailJSON 		= file_get_contents("php://input");
		$reviewInfo 			= json_decode($reviewDetailJSON, true);
		$data					= array();
		$insertData				= $reviewInfo['reviewInfo'];
		$insertData['checked']	= 'N';
		/*if( $insertData['rating_val'] >= $this->config->item('avg_rating') ){
			$insertData['avg']		= 'above';
		}
		else{
			$insertData['avg']		= 'below';		
		}*/
		$log_id = $insertData['log_id'];
		$venue_id = $insertData['venue_id'];
		unset($insertData['log_id']);	
		unset($insertData['rating']);	
		unset($insertData['v_feed_back']);	
		unset($insertData['v_rating_val']);	
		unset($insertData['venue_id']);	
		$insertData['created_time'] = date('Y-m-d H:i:s');
		$response				= array();	
		
		$data['log_id'] = $insertData['scheduled_log_id'];
		$checkNoShow = $this->feedback_model->checkNoshow($insertData['given_user'],$data['log_id'],$insertData['member_id']);
		if($checkNoShow && $checkNoShow->no_show == 'Y'){
			$response = array('status' => 'noshow', 'message' => $this->config->item('noshowUpdatedMessage'), 'result' => $result);
		}
		elseif($checkNoShow && $checkNoShow->no_show == 'N' && $insertData['no_show'] == 'Y'){
			$response = array('status' => 'noshow', 'message' => $this->config->item('noshowUpdatedMessage2'),
				 'result' => $result);
		}
		else{
			$feed_id = $this->feedback_model->saveFeedback($insertData);
			$this->feedback_model->update_schedule_log_state($data['log_id'],$insertData['given_user']);
			
			if($insertData['no_show'] == 'Y')
			{				
				$this->feedback_model->removeVenueFeedBack($venue_id,$insertData['given_user']);
				$action_date = $this->feedback_model->getLogdate($data['log_id']);
				//echo "<pre>"; print_r($action_date);echo "</pre>"; exit;
				$result = insertPoint(array('id' => 5, 'member_id' => $insertData['member_id']), $data['log_id'],$action_date);
				
			}
			else{				
				$this->feedback_model->removePointsFromDBForNoShow(5,$insertData['member_id'],$data['log_id']);	
			}
			
			$response = array('status' => 'ok', 'message' => 'Data Saved', 'feed_id'=>$feed_id , 'result' => $result);
		}
		
		echo json_encode($response);	 exit();			
	}
	
	
	function saveVenueReview(){
		$this->load->model('mobile/venue_model');
		$this->load->model('mobile/feedback_model');
		$reviewDetailJSON 		= file_get_contents("php://input");
		$reviewInfo 			= json_decode($reviewDetailJSON, true);
		$data					= array();
		$insertData				= $reviewInfo['reviewInfo'];
		
		$insertData['feed_back'] = $insertData['v_feed_back'];
		$insertData['rating_val'] = $insertData['v_rating_val'];
		
		$log_id = $insertData['log_id'];
		unset($insertData['log_id']);	
		unset($insertData['no_show']);	
		unset($insertData['rating']);
		unset($insertData['member_id']);	
		unset($insertData['v_feed_back']);	
		unset($insertData['v_rating_val']);	
		
		
		$insertData['created_time'] = date('Y-m-d H:i:s');
		$response				= array();				
		
		if($result = $this->feedback_model->saveVenueFeedback($insertData))
		{
			$response = array('status' => 'ok', 'message' => 'Data Saved', 'result' => $result);
			
		}
		else
		{
			$response = array('status' => 'error', 'message' => 'Database Error', 'result' => $result);
		}
		echo json_encode($response);	 exit();			
	}
	function getProfile(){
		
		$profileIdJSON 				= file_get_contents("php://input");
		$profileid 					= json_decode($profileIdJSON, true);
		$deviceDetails				= $profileid['deviceDetails'];		
		$profileid					= $profileid['userId'];		
		$response					= array();
		
		
		########################
		/*$file = time();
		//echo FCPATH."log/".$file.".txt"; exit;
		$myfile = fopen(FCPATH."log/".$file.".txt", "w") or die("Unable to open file!");
		$txt = "Log text";
		fwrite($myfile, $txt);
		fclose($myfile);*/
		########################
		$uData = $this->user_model->getUserDetailsFromDeviceId($deviceDetails['device_id']);
		//echo "<pre>"; print_r($uData ); echo "</pre>"; exit;
		if(empty($uData)){
			$response['status'] = 'nouser';
		}
		elseif($uData->is_block == 'Y'){
			$response['status'] = 'blocked';
			$response['message'] = $this->config->item('BlockedUserMessage');
		}
		elseif($uData->status == 'T'){
			$response['status'] = 'trashed';
			$response['message'] = $this->config->item('TrashedUserMessage');
		}
		elseif($uData->status == 'Y'){
			$this->user_model->updateDeviceDetails($uData->member_id,$deviceDetails);
			if(!$uData->contact_number || $uData->contact_number == '' || empty($uData->gender)){
				$response['status'] = 'nocontact';
			}
			else{
				$prev_action	= $this->availability_model->checkAnyPrevAction($uData->member_id);
				$date		   = $prev_action->currentdate?$prev_action->currentdate:date("Y-m-d");
				$prev_availid   = $prev_action->match_logid?$prev_action->match_logid:$uData->prev_availid;
				$availability   = $this->availability_model->getAvailabilityForApp($uData->member_id,$date,$prev_availid);
				$uData->availability = $availability ;
				//echo "<pre>"; print_r($availability ); echo "</pre>"; exit;
				if(empty($availability) || empty($availability['match_logid'])){ // || empty($availability['matches'])
					$response['status'] = 'setavailability';
				}
				elseif($availability['review_status'] == 'N'){
					$response['status'] = 'setreview';
				}
				elseif(empty($availability['review_status']) && $availability['matches'] > 0){
					$response['status'] = 'viewmatch';
				}
				elseif(!empty($availability['temp_match_status']) && $availability['temp_match_status'] == 'N'){
					$match_logid = $availability['match_logid']; 
					$tempLog  			= $this->availability_model->getRecentMatchFromLogid($match_logid);
					
					if($tempLog){
						$uData->tempLog = $tempLog ;
						$logTimeDiff 		      = strtotime(date('Y-m-d H:i:s')) - strtotime($tempLog['created_time']);						
						$logTimeDiffMin     	   = round(abs($logTimeDiff) / 60,0);
						//echo "<pre>"; print_r($logTimeDiffMin ); echo "</pre>"; exit;
						if($logTimeDiffMin > 15){
							$nomatchId = $this->availability_model->getNoMatchLog($uData->member_id, $match_logid);
							if($nomatchId > 0)
								$uData->no_matches = 'Y';
							$this->availability_model->update_nomatch_log_read($uData->member_id);
							$response['status'] = 'setavailability';
						}
						else{
							if($match_logid == $tempLog['from_match_logid']){
								$uData->tempLog['matchuser_id'] 		= $tempLog['to_match_logid'];
								$uData->tempLog['matchuser_status']    = $tempLog['to_match_status'];
								$uData->tempLog['my_status']		   = $tempLog['from_match_status'];
								$uData->tempLog['notfic_time']		 = $tempLog['created_time'];	
							}
							else{
								$uData->tempLog['matchuser_id']		= $tempLog['from_match_logid'];
								$uData->tempLog['matchuser_status']    = $tempLog['from_match_status'];
								$uData->tempLog['my_status']		   = $tempLog['to_match_status'];
								$uData->tempLog['notfic_time']		 = $tempLog['created_time'];
							}
							$response['status'] = 'matchprofile';
						}						
					}
					else{
							$nomatchId = $this->cron_model->getNoMatchLog($uData->member_id, $match_logid);
							if($nomatchId > 0){
								$uData->no_matches = 'Y';
								$response['status'] = 'nomatches';
								$response['message'] = $this->config->item('NoMatchPushNotificationMessage');
								$this->availability_model->update_nomatch_log_read($nomatchId);
							}
					}
					
				}
				else{
					$response['status'] = 'setavailability';
				}
			
			}
			$response['result'] 		= $uData;
		}
		
		
		
		echo json_encode($response); exit();
	}
	function updateDeviceDetails($profileid,$deviceDetails){
		$this->user_model->updateDeviceDetails($profileid,$deviceDetails);
	}
	function saveDeviceToken(){
		$postDataJSON 			= file_get_contents("php://input");
		$postData				= json_decode($postDataJSON, true);
		$deviceDetails			= $postData['deviceDetails'];
		if($result = $this->user_model->updateDeviceDetailsbyDeviceId($deviceDetails))
		{
			$response = array('status' => 'ok', 'message' => 'Data Updated', 'result' => $result);
		}
		else
		{
			$response = array('status' => 'error', 'message' => 'Database Error', 'result' => $result);
		}
		echo json_encode($response); exit();
	}
	function getVenue(){
		
		$postDataJSON 			= file_get_contents("php://input");
		$postData				= json_decode($postDataJSON, true);
		$venue_id				= $postData['venue_id'];
		$selected				= $postData['selected'];
		$favourite			   = $postData['favourite'];
		
		$venue 	= 	$this->user_model->getVenueDet($venue_id);
		
			$api_key = $this->config->item('google_place_api_key');
			$google_api_url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=".$venue_id."&key=".$api_key;				
			$venueDetails =	file_get_contents($google_api_url);
			$venueDetails = json_decode($venueDetails,true);
			
			$venueDetails['name'] 			= $venueDetails['result']['name'];
			$venueDetails['address'] 		 = strip_tags($venueDetails['result']['adr_address']);
			$venueDetails['place_id'] 		= $venueDetails['result']['place_id'];
			$venueDetails['phone_number'] 	= $venue->phone?$venue->phone:$venueDetails['result']['formatted_phone_number'];
			$venueDetails['image'] 		   = $venue->image?$venue->image:($venueDetails['result']['photos'][0]['photo_reference']?'https://maps.googleapis.com/maps/api/place/photo?maxheight=360&photoreference='.$venueDetails['result']['photos'][0]['photo_reference'].'&key='.$api_key:'');
			$venueDetails['selected'] 		= $selected;
			$venueDetails['favourite'] 	   = $favourite;
		
		if($venueDetails){	
			$response = array('status' => 'success', 'message' => 'Date Fetched', 'result' => $venueDetails);
		}
		else
		{
			$response = array('status' => 'error', 'message' => 'No venues');
		}
		echo json_encode($response); exit();
	}
	function getUserDetailForMatchProfile(){
		
	
		//echo "<pre>"; print_r($venueDetails); echo "</pre>"; exit;
		
		$postDataJSON 			= file_get_contents("php://input");
		$postData				= json_decode($postDataJSON, true);
		$user_id				= $postData['user_id'];
		$member_id				= $postData['member_id'];
		$logId					= $postData['logId'];
		$response				= array();
		$result = $this->user_model->getMeetingDetails($user_id, $member_id, $logId);
		//echo "<pre>"; print_r($result); echo "</pre>"; exit;
		if(empty($result)){
			$response = array('status' => 'setavailability');
		}
		elseif($result->myStatus == 'N'){
			$response = array('status' => 'setreview');
		}
		elseif($result->myStatus == 'Y'){
			$response = array('status' => 'setavailability');
		}
		else{

			$server_timezone = date_default_timezone_get();
			$result->match_time_from = $this->convertTimeToSiteFormat($server_timezone,$result->start_time ,$result->myTimezone);
			$result->match_time_to = $this->convertTimeToSiteFormat($server_timezone,$result->time_to,$result->myTimezone);
			$venue 	= 	$this->user_model->getVenueDet($result->venue_id);
			
			$api_key = $this->config->item('google_place_api_key');
			$google_api_url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=".$result->venue_id."&key=".$api_key;				
			$venueDetails =	file_get_contents($google_api_url);
			$venueDetails = json_decode($venueDetails,true);
			$venueDetails['name'] 			= $venueDetails['result']['name'];
			$venueDetails['address'] 		 = strip_tags($venueDetails['result']['adr_address']);
			$venueDetails['phone_number'] 	= $venue->phone?$venue->phone:$venueDetails['result']['formatted_phone_number'];
			$venueDetails['image'] 	= $venue->image;
			
			$rate = round($venueDetails['result']['rating']);
			$rating_orange = $rate;
			$rating_gray   = 5-$rate;
			$venueDetails['rating_orange']   = $rating_orange;
			$venueDetails['rating_gray']     = $rating_gray;
			$venueDetails['place_id'] 		= $venueDetails['result']['place_id'];
			$venueDetails['photo_reference'] = $venueDetails['result']['photos'][0]['photo_reference'];
			
			$result->venue 		= $venueDetails;
			$response = array('status' => 'success', 'message' => sprintf($this->config->item('LunchMeetingScheduledMessage')
			, $name = ucwords($result->myName) ), 'result' => $result);
		}
		
		echo json_encode($response); exit();
	}
	function getMatchDetails(){
		$this->load->model('mobile/venue_model');
		$postDataJSON 			= file_get_contents("php://input");
		$postData				= json_decode($postDataJSON, true);
		$logid					= $postData['logId'];
		$tomatchLogId			= $postData['tomatchLogId'];
		$member_id				= $postData['userId'];
		$status					= $postData['status'];
		$response				= array();
		
		$matchDet 				= $this->user_model->getMatchDetails($tomatchLogId,$logid);
		$tempLog				 = $this->user_model->get_field('temp_matching_log','*','log_id = '.$logid);
		
		
		$matchDet->tempLog       = $tempLog;
		if($tomatchLogId == $tempLog['to_match_logid']){
			$to_log_id 		= $tempLog['to_match_logid'];
			$from_log_id 	  = $tempLog['from_match_logid'];
			$matcher_status   = $tempLog['to_match_status'];
			$my_status 		= $tempLog['from_match_status'];
		}				
		elseif($tomatchLogId == $tempLog['from_match_logid']){
			$to_log_id      = $tempLog['from_match_logid'];
			$from_log_id    =  $tempLog['to_match_logid'];
			$matcher_status = $tempLog['from_match_status'];
			$my_status 	  = $tempLog['to_match_status'];
		}
			
		
		if($tempLog){
						
			$server_timezone = date_default_timezone_get();
			$matchDet->match_time_from = $this->convertTimeToSiteFormat($server_timezone,$matchDet->match_time_from,$matchDet->timezone);
			$matchDet->match_time_to   = $this->convertTimeToSiteFormat($server_timezone,$matchDet->match_time_to,$matchDet->timezone);
			$matchDet->matcher_status  = $matcher_status;
			$matchDet->my_status       = $my_status;
			//$match_venue_id = $this->venue_model->getMatchScheduleVenues($from_log_id,$to_log_id);
			//$result->match_venue_id = $match_venue_id['venue_id'];
			
			$seconds = strtotime($tempLog['created_time']) - strtotime(date('Y-m-d H:i:s'));
			$matchDet->remaining_time = 15 - round(abs($seconds) / 60,0);
			
			if($matchDet->remaining_time <= 0)
				$matchDet->remaining_time = 0;
					
			$response = array('status' => 'ok', 'message' => 'Date Fetched', 'result' => $matchDet);
									
		}
		else
		{
			$response = array('status' => 'error', 'message' => $this->config->item('NotAvailableMatchNow'), 'result' => $matchDet);
		}
		echo json_encode($response); exit();
	}
	function getMatchRemainingTime(){
		$postDataJSON 			= file_get_contents("php://input");
		$postData				= json_decode($postDataJSON, true);
		$logid					= $postData['tempLog'];
		
		if($createdTime = $this->user_model->get_field('temp_matching_log','*','log_id = '.$logid))
		{
			$seconds = strtotime($createdTime['created_time']) - strtotime(date('Y-m-d H:i:s'));
			$remaining_time = 15 - round(abs($seconds) / 60,0);
			$remaining_time	= ($remaining_time<0)?0:$remaining_time;
			$response = array('status' => 'ok', 'message' => 'Date Fetched', 'result' => $remaining_time);
		}		
		else
		{
			$response = array('status' => 'error', 'message' => 'Database Error', 'result' => $result);
		}
		echo json_encode($response); exit();
	}
	function getMatchReviewDetails(){
		$this->load->model('mobile/feedback_model');
		$postDataJSON 			= file_get_contents("php://input");
		$postData				= json_decode($postDataJSON, true);
		$logid					= $postData['logId'];
		$matcherId				= $postData['matcherId'];	
		$memberId				= $postData['memberId'];	
		//echo "<pre>"; print_r($postData); echo "</pre>"; exit;
		$response				= array();
		$checknoShow = $this->feedback_model->checkNoshow($memberId,$logid,$matcherId);
		$result = $this->user_model->getMatcherDetails($logid, $matcherId,$memberId);
			
		$server_timezone = date_default_timezone_get();
		$result->schedule_timefrom = $this->convertTimeToSiteFormat($server_timezone,$result->start_time,$result->myTimezone);
		$result->schedule_timeto = $this->convertTimeToSiteFormat($server_timezone,$result->schedule_timeto,$result->myTimezone);
				
		if($checknoShow && $checknoShow->no_show == 'Y'){
			$response = array('status' => 'noshow', 'message' => $this->config->item('noshowUpdatedMessage'), 'result' => $result);
		}
		else{
			$response = array('status' => 'ok', 'message' => 'Date Fetched', 'result' => $result);
		}
		echo json_encode($response); exit();
	}
	function getMatchVenues(){
		$this->load->model('mobile/venue_model');
		$postDataJSON 				= file_get_contents("php://input");
		$postData 					= json_decode($postDataJSON, true);
		$tomatchLogId				= $postData['tomatchLogId'];
		$logId						= $postData['logId'];
		$response					= array();
		$venueDetails				= array();
		
		//$result = $this->user_model->get_field('temp_matching_log','*',"log_id = $logId AND (from_match_logid = $tomatchLogId OR to_match_logid = $tomatchLogId)");
		
		if($matchVenues = $this->venue_model->getMatchVenueDetails($tomatchLogId))
		{			
			foreach($matchVenues as $matchVenue)
			{
				$google_api_url ="https://maps.googleapis.com/maps/api/place/details/json?placeid=".$matchVenue['venue_id']."&key=AIzaSyCSB01UwvVbd63eV_scq-rOD4AEirD8z9Q";				
				$venueDetails[]	=	file_get_contents($google_api_url);
			}
			$response = array('status' => 'ok', 'message' => 'Date Fetched', 'result' => $venueDetails);
		}
		else
		{
			$response = array('status' => 'error', 'message' => 'Database Error', 'result' => $result);
		}
		echo json_encode($response); exit();
	}
	function matchAccepted(){
	 	$this->load->model('mobile/cron_model');
		$this->load->model('mobile/venue_model');
		$acceptInfoJSON 		= file_get_contents("php://input");
		$acceptInfo 			= json_decode($acceptInfoJSON, true);
		$fromUserId				= $acceptInfo['fromUserId'];
		$logId					= $acceptInfo['logId'];
		$tomatchLogId			= $acceptInfo['tomatchLogId'];
		$response				= array();
		$data					= array();
		
		if($result = $this->user_model->get_field('temp_matching_log','*','log_id = '.$logId))
		{
			
			//var_dump($result['created_time']);	
			//var_dump(date('Y-m-d H:i:s'));
			$seconds = strtotime($result['created_time']) - strtotime(date('Y-m-d H:i:s'));
			$remaining_time = 15 - round(abs($seconds) / 60,0);
			if((int)$remaining_time <= 0)
			{
				$remaining_time	= ($remaining_time<0)?0:$remaining_time;
				//$this->insertPointsToDB(4,$fromUserId);
				$response = array('status' => 'expired', 'message' => $this->config->item('MatchExpiredMessage'), 'result' => $result);
				echo json_encode($response);
				exit;
			}
			
			$temp=array_search($tomatchLogId,$result);
			if($temp == 'from_match_logid')
				{
				 $notifuserlogid = $result['from_match_logid'];
				 $data['to_match_status'] = 'Y';
				 $matchofnotifuser = $result['to_match_logid'];
				}
				else if($temp == 'to_match_logid')
				{
				 $notifuserlogid = $result['to_match_logid'];
				 $data['from_match_status'] = 'Y';	
				 $matchofnotifuser = $result['from_match_logid'];
				}
				else
				{
					$response = array('status' => 'error', 'message' => 'Could not update! Match Log not found!', 'result' => 'false');
					echo json_encode($response);
					exit;
				}
			if($result = $this->cron_model->update_temp_log($data,$logId))
			{
				$response = array('status' => 'ok', 'message' => 'Date Updated', 'result' => $result);
				
				$result = $this->user_model->get_field('temp_matching_log','*','log_id = '.$logId);
				if($result['from_match_status'] == 'Y' && $result['to_match_status'] == 'Y')
				{
					$from_match_detail 	= $this->user_model->get_field('match_log_master','*','match_logid = '.$result['from_match_logid']);
					$to_match_detail 	= $this->user_model->get_field('match_log_master','*','match_logid = '.$result['to_match_logid']);
					$venue 				= $this->venue_model->getMatchScheduleVenues($result['from_match_logid'],$result['to_match_logid']);
					
					$insertData ['from_log_id'] 		= $result['from_match_logid'];
					$insertData ['to_log_id'] 			= $result['to_match_logid'];
					$insertData ['from_user'] 			= $from_match_detail['member_id'];
 					$insertData ['to_user'] 			= $to_match_detail['member_id'];
					$insertData ['venue_id'] 			= $venue['venue_id'];
					$insertData ['schedule_timefrom'] 	= $from_match_detail['match_time_from'];
					$insertData ['schedule_timeto'] 	= $from_match_detail['match_time_to'];
					$insertData ['created_time'] 	   = date("Y-m-d h:i:s"); 
					$vsl_logId = $this->user_model->insert_fields ('venue_sceduled_log',$insertData);
					if($vsl_logId){
						$this->cron_model->updateVenueMeetingCount($venue['venue_id']);
						insertPoint(array('id' => 2, 'member_id' => $insertData ['from_user']),$vsl_logId);
						insertPoint(array('id' => 2, 'member_id' => $insertData ['to_user']),$vsl_logId);
					}
					$myName 	= $this->user_model->get_field('member_master','formatted_name','member_id = '.$fromUserId);
					
					$payload = array(
						'title' => "Match Response",
						'message'=> sprintf($this->config->item('LunchMeetingScheduledMessage'), $name = ucwords($myName['formatted_name']) )					
					);
					$user = $this->user_model->getUserDetails($to_match_detail['member_id']);
					//	$payload['anchor'] = '/app/matchdetail/'.$logId.'/'.$result['to_match_logid'];
					$payload['anchor'] = '/app/matchdetail/'.$vsl_logId.'/'.$from_match_detail['member_id'];
					$this->_initNotifications($user,$payload);
					$user = $this->user_model->getUserDetails($from_match_detail['member_id']);
				
					$payload['anchor'] = '/app/matchdetail/'.$vsl_logId.'/'.$to_match_detail['member_id'];
					$this->_initNotifications($user,$payload);
				}
				/*else
				{
					 $notifuserlog 	= $this->user_model->get_field('match_log_master','*','match_logid = '.$notifuserlogid);
					 $user = $this->user_model->getUserDetails($notifuserlog['member_id']);
					 $payload = array(
						'title' => "Match Response",
						'message'=> 'Other user have accepted your profile !',
						'anchor' => '/app/matchprofile/'.$logId.'/'.$matchofnotifuser.'/accepted',
					);
					$this->_initNotifications($user,$payload);
				}*/
			}
			else
			{
				$response = array('status' => 'error', 'message' => 'Database Error1', 'result' => $result);
			}
		}
		else
		{
			$response = array('status' => 'error', 'message' => 'Database Error2', 'result' => $result);
		}			
		echo json_encode($response); exit();
	}
	function matchRejected()
	{
		$postDataJSON 		= file_get_contents("php://input");
		$postData 			= json_decode($postDataJSON, true);
		$member_id			= $postData['userId'];
		$logId				= $postData['logId'];
		$tomatchLogId		= $postData['tomatchLogId'];
		$this->load->model('cron_model');
		
		if($result = $this->user_model->get_field('temp_matching_log','*','log_id = '.$logId))
		{
			$seconds = strtotime($result['created_time']) - strtotime(date('Y-m-d H:i:s'));
			$remaining_time = 15 - round(abs($seconds) / 60,0);
			if((int)$remaining_time <= 0)
			{
				$remaining_time	= ($remaining_time<0)?0:$remaining_time;
				$response = array('status' => 'expired', 'message' => 'This Match has expired', 'result' => $result);
				echo json_encode($response);
				exit;
			}
			
			$userPoints = $this->user_model->getTotalPoints($postData['userId']);
			$matchrejection_min_point = $this->config->item('matchrejection_min_point')?$this->config->item('matchrejection_min_point'):10;
			if($userPoints > $matchrejection_min_point){
			
				$temp=array_search($tomatchLogId,$result);
				if($temp == 'from_match_logid'){
					 $notifuserlogid = $result['from_match_logid'];
					 $data['to_match_status']   = 'N';
					// $data['from_match_status'] = 'Y';
					 $matchofnotifuser = $result['to_match_logid'];
				}
				else if($temp == 'to_match_logid'){
					 $notifuserlogid = $result['to_match_logid'];
					 $data['from_match_status'] = 'N';
					// $data['to_match_status']   = 'Y';
					 $matchofnotifuser = $result['from_match_logid'];
				}
				else{
					$response = array('status' => 'error', 'message' => 'Could not update! Match not found!', 'result' => 'false');
					echo json_encode($response);
					exit;
				}
								
				if($result = $this->cron_model->update_temp_log($data,$logId))
				{
					//$this->insertPointsToDB2(3,$postData['userId']);
					$notifuserlog 	= $this->user_model->get_field('match_log_master','*','match_logid = '.$notifuserlogid);
					/*$user = $this->user_model->getUserDetails($notifuserlog['member_id']);	
					$payload = array(
						'title' => "Match Response",
						'message'=> $this->config->item('MatchRejectedMessage'),
						'anchor' => '/app/matchprofile/'.$logId.'/'.$matchofnotifuser.'/rejected',
					);
					$this->_initNotifications($user,$payload);*/
					insertPoint(array('id' => 3, 'member_id' => $postData['userId']),$notifuserlog['member_id']);
					$response = array('status' => 'ok', 'message' => 'Date Updated', 'result' => $result);				
				}
				else
				{
					$response = array('status' => 'error', 'message' => 'Database Error1', 'result' => $result);
				}
			}
			else{
				$message = $this->config->item('MinimumRejectPointMessage');
				$response['status'] 	= 'cannotset';
				$response['message'] 	= $message;
				echo json_encode($response);
				exit;
			}
		}
		else
		{
			$response = array('status' => 'error', 'message' => 'Database Error2', 'result' => $result);
		}
					
		echo json_encode($response); exit();
	}
	function getPreferences(){
		$profileIdJSON 				= file_get_contents("php://input");
		$profileid 					= json_decode($profileIdJSON, true);
		$profileid					= $profileid['member_id'];
		$response					= array();
		
		if($result = $this->user_model->get_preference($profileid))
		{
			if($result['notification_time'] == '00:00:00')
				$result['notification_time'] = '';
			$result['notification_time'] = $result['notification_time']?$result['notification_time']:'09:30:30';
			$result['get_notification'] = ($result['get_notification']!='')?$result['get_notification']:'1';
			
			if($result['notification_time'] != '')
				$result['notification_time'] = $this->convert($result['notification_time'],'24');
			
			if($companies = $this->user_model->get_fields('member_excluded_comapnies','*','member_id = '.$profileid))
			{
				$companyTemp = array();
				$i=0;
				foreach ($companies as $company)
				{
				$companyTemp[$i]['id'] 		= $company['company_id'];
				$companyTemp[$i]['name'] 	= $company['company_name'];
				$companyTemp[$i]['logoUrl'] = $company['company_logourl'];
				$i++;
				}
				$result['companies'] = $companyTemp;
				
			}
			$response = array('status' => 'ok', 'message' => 'Date Fetched', 'result' => $result);
			/*else
			{
				$response = array('status' => 'error', 'message' => 'Database Error member_excluded_comapnies', 'result' => $result['companies']);
			}*/
			
		}
		else
		{
			$response = array('status' => 'error', 'message' => 'Database Error', 'result' => $result);
		}
		echo json_encode($response); exit();
	}
	function updateExeComp(){
		$profileJSON 				= file_get_contents("php://input");
		$profile 					= json_decode($profileJSON, true);
		$member_id				  = $profile['member_id'];
		$companies    			  = $profile['companies'];	
		foreach($companies as $company)					
					{
						$insertData = array();
						$insertData['member_id'] 	   = $member_id;
						$insertData['company_id'] 	  = $company['id'];
						$insertData['company_name'] 	= $company['name'];
						$insertData['company_logourl'] = $company['logoUrl'];
					
						$result = $this->user_model->insert_fields('member_excluded_comapnies',$insertData);
					}
		$response = array('status' => 'ok', 'message' => 'Exclude companies added succesfully.', 'result' => $result);		
		
		echo json_encode($response); exit();
	
	}
	function updateProfile(){
		$profileJSON 				= file_get_contents("php://input");
		$profile 					= json_decode($profileJSON, true);
		$member_id				  = $profile['member_id'];	
		$userProfile				= $profile['userProfile'];	
		$response				   = array();
		$insertData				 = array();
		$insertData['member_id']	= $member_id;
		$insertData['gender']	   = $preference['gender'];
		$insertData['first_name']   = $userProfile['firstName'];
		$insertData['last_name']    = $userProfile['lastName'];
		$insertData['maiden_name']  = $userProfile['maidenName'];
		$insertData['formatted_name']= $userProfile['formattedName'];
		$insertData['headline']	  = $userProfile['headline'];
		$insertData['email']		 = $userProfile['emailAddress'];
		$insertData['picture_url']	= $userProfile['pictureUrl'];	
		$insertData['profile_url']	= $userProfile['profile_url'];			
		$result = $this->user_model->update_fields('member_master',$insertData);		
		$this->user_model->delete_row('member_current_positions','member_id ='.$insertData['member_id']);
		
		if($userProfile['positions']['values']){
			foreach($userProfile['positions']['values'] as $currentPosition){
					$insert = array();
					$insert['member_id'] 	= $member_id;
					$insert['company_id'] 	= $currentPosition['company']['id'];
					$insert['company_name'] = $currentPosition['company']['name'];
					$insert['position_id'] 	= $currentPosition['id'];
					$insert['position_title'] = $currentPosition['title'];
					$insert['is_current'] 	= $currentPosition['isCurrent']?'Y':'N';
				
					$this->user_model->insert_fields('member_current_positions',$insert);
			}
		}
					
		$insertData						= array();
		$insertData['location']			= $userProfile['location']['name'];
		$insertData['industry']			= $userProfile['industry'];
					
		$temp = $this->user_model->get_field( 'member_profile','member_id','member_id = '.$profile['member_id']);
		if(!empty($temp))
		{
			$result = $this->user_model->update_fields('member_profile',$insertData);
		}
		else
		{
			$result = $this->user_model->insert_fields('member_profile',$insertData);
		}
		
		if(!$result)
		{
			$response = array('status' => 'error', 'message' => 'Database Error', 'result' => $result);	
		}
		else
		{
			$response = array('status' => 'ok', 'message' => 'Your profile has been updated successfully.', 'result' => $result);		
		}
		echo json_encode($response); exit();
	}
	function savePreferences(){
		$preferenceJSON 				= file_get_contents("php://input");
		$preference 					= json_decode($preferenceJSON, true);
		
		$companies						= $preference['companiesData'];
		$userProfile					= $preference['userProfile'];
		$preference						= $preference['preferenceData'];		
		$response						= array();
		$insertData						= array();
		$insertData['member_id']		= $preference['member_id'];
		$insertData['gender']			= $preference['gender'];
		$insertData['email']			= $preference['email'];
		$insertData['first_name']		= $userProfile['firstName'];
		$insertData['last_name']		= $userProfile['lastName'];
		$insertData['maiden_name']		= $userProfile['maidenName'];
		$insertData['formatted_name']	= $userProfile['formattedName'];
		$insertData['headline']			= $userProfile['headline'];		
		$insertData['picture_url']		= $userProfile['pictureUrl'];
		$insertData['profile_url']	= $userProfile['profile_url'];		
		
					
		$result = $this->user_model->update_fields('member_master',$insertData);
		
		
		$this->user_model->delete_row('member_current_positions','member_id ='.$insertData['member_id']);
		if($userProfile['positions']['values']){
			foreach($userProfile['positions']['values'] as $currentPosition){
					$insert = array();
					$insert['member_id'] 	= $preference['member_id'];
					$insert['company_id'] 	= $currentPosition['company']['id'];
					$insert['company_name'] = $currentPosition['company']['name'];
					$insert['position_id'] 	= $currentPosition['id'];
					$insert['position_title'] = $currentPosition['title'];
					$insert['is_current'] 	= $currentPosition['isCurrent']?'Y':'N';
				
					$this->user_model->insert_fields('member_current_positions',$insert);
				}
		}
					
		$insertData						= array();
		$insertData['member_id']		= $preference['member_id'];
		$insertData['contact_number']	= $preference['phoneNumber'];
		$insertData['location']			= $userProfile['location']['name'];
		$insertData['industry']			= $userProfile['industry'];
					
		$temp = $this->user_model->get_field( 'member_profile','member_id','member_id = '.$preference['member_id']);
		if(!empty($temp))
		{
			$result = $this->user_model->update_fields('member_profile',$insertData);
		}
		else
		{
			$result = $this->user_model->insert_fields('member_profile',$insertData);
		}
		
		$insertData						= array();
		$insertData['member_id']		= $preference['member_id'];
		$insertData['exclude_pre_match']	= $preference['excludePreviousMatches'];
		$insertData['gender_exclude']		= $preference['excludeGender'];
		$insertData['notification_time']	= $this->convert($preference['notificationTime'],12);
		$insertData['get_notification']	= $preference['get_notification'];
		
		$temp = $this->user_model->get_field( 'member_preferences','member_id','member_id = '.$preference['member_id']);
		if(!empty($temp))
		{
			$result = $this->user_model->update_fields('member_preferences',$insertData);
		}
		else
		{
			$result = $this->user_model->insert_fields('member_preferences',$insertData);
		}
		/*$result = $this->user_model->delete_row('member_excluded_comapnies','member_id = '.$preference['member_id']  );
		foreach($companies as $company)					
					{
						$insertData = array();
						$insertData['member_id'] 		= $preference['member_id'];
						$insertData['company_id'] 		= $company['id'];
						$insertData['company_name'] 	= $company['name'];
						$insertData['company_logourl'] 	= $company['logoUrl'];
					
						$result = $this->user_model->insert_fields('member_excluded_comapnies',$insertData);
					}*/
		if(!$result)
		{
			$response = array('status' => 'error', 'message' => 'Database Error', 'result' => $result);	
		}
		else
		{
			$response = array('status' => 'ok', 'message' => 'Database Updated', 'result' => $result);		
		}
		echo json_encode($response); exit();
	}
	function deleteCompany(){
		$preferenceJSON 				= file_get_contents("php://input");
		$preference 					= json_decode($preferenceJSON, true);
		
		$company_id						= $preference['company_id'];
		$member_id						 = $preference['member_id'];
		$result = $this->user_model->delete_row('member_excluded_comapnies','member_id = '.$member_id.' AND company_id = '.$company_id  );
		if(!$result)
		{
			$response = array('status' => 'error', 'message' => 'Database Error', 'result' => $result);	
		}
		else
		{
			$response = array('status' => 'ok', 'message' => 'Database Updated', 'result' => $result);		
		}
		echo json_encode($response); exit();
	}
	function login(){
		$this->load->library('email_lib');
		$profileDataJSON 				= file_get_contents("php://input");
		$profileData 					= json_decode($profileDataJSON, true);
		$username					   = $profileData['username'];
		$password					   = $profileData['password'];
		$result						 = $this->user_model->get_field('member_master','*','email = "'.$username.'"');
		if($result['password']  == $password ){
			$result['authentication']  = 'form';
			$response = array('status' => 'ok', 'message' => 'User Exists', 'result' => $result);
			
		}
		else
		{
			$response = array('status' => 'fail', 'message' => 'Invalid email or password!', 'result' => $result);
			
		}
		echo json_encode($response); exit();
	}
	function saveProfile(){
		$this->load->library('email_lib');
		$profileDataJSON 				= file_get_contents("php://input");
		$profileData 					= json_decode($profileDataJSON, true);
		$profileData					= $profileData['profileData'];
		$result							= $this->user_model->get_field('member_master','*','auth_id = "'.$profileData['id'].'"');
		$response						= array();
		
		$insertData						= array();
		$insertData['auth_id']			= $profileData['id'];
		$insertData['auth_type']		= 'linkedin';
		$insertData['first_name']		= $profileData['firstName'];
		$insertData['last_name']		= $profileData['lastName'];
		$insertData['maiden_name']		= $profileData['maidenName'];
		$insertData['formatted_name']	= $profileData['formattedName'];
		$insertData['headline']			= $profileData['headline'];
		$insertData['email']			= $profileData['emailAddress'];
		$insertData['gender']			= $profileData['gender']?$profileData['gender']:'';
		$insertData['picture_url']		= $profileData['pictureUrl'];
		$insertData['profile_url']		= $profileData['profile_url'];
		$insertData['access_token']		= $profileData['accesToken'];
		$insertData['device_platform']	= $profileData['devicePlatform'];
		$insertData['device_token']		= $profileData['deviceToken'];
		$insertData['device_id']		= $profileData['deviceId'];
		
		$this->user_model->removeExistingToken($profileData['deviceToken']);
		if(empty($result)){
			$insertData['created_time']		= date('Y-m-d h:i:s');
			
			if($result = $this->user_model->insert($insertData)){
					// Insert profile data
					//$this->user_model->insertDetails(array('member_id' => $result));
					insertPoint(array('id' => 1, 'member_id' => $result),$result);
					$insert = array();
					$insert['member_id'] 	= $result;
					$insert['location']		= $profileData['location']['name'];
					$insert['industry']		= $profileData['industry'];
					$this->user_model->insert_fields('member_profile',$insert);
					
					foreach($profileData['threeCurrentPositions']['values'] as $currentPosition)					
					{
						$insert = array();
						$insert['member_id'] 	= $result;
						$insert['company_id'] 	= $currentPosition['company']['id'];
						$insert['company_name'] = $currentPosition['company']['name'];
						$insert['position_id'] 	= $currentPosition['id'];
						$insert['position_title'] = $currentPosition['title'];
						$insert['is_current'] 	= $currentPosition['isCurrent'];
					
						$this->user_model->insert_fields('member_current_positions',$insert);
					}
					$profileData['member_id']= $result;
					$result	= $this->user_model->get_field('member_master','*','auth_id = "'.$profileData['id'].'"');
					$response['result']		= $result;
					$response['status'] 	= 'ok';
					$response['message'] 	= 'Data Inserted';
					
					
					$insertData['name']		 = $insertData['formatted_name'];
					$insertData['industry']  = $profileData['industry'];
					$insertData['country']		 = $profileData['location']['name'];	
					$this->email_lib->registrationSuccessMailToAdmin($insertData);	
					$this->email_lib->registrationSuccessMailToUser($insertData);	
			
					}
					else
					{
					$response = array('status' => 'error', 'message' => 'Database Error', 'result' => $result);
					}
		}
		else
		{
			$this->user_model->update_by(array('auth_id'=>$profileData['id']),$insertData);
			$result	= $this->user_model->get_field('member_master','*','auth_id = "'.$profileData['id'].'"');
			$response = array('status' => 'ok', 'message' => 'User Exists', 'result' => $result);
		}
		echo json_encode($response); exit();
	}
	function saveAvailability(){
		
		$AvailabilityDataJSON 				= file_get_contents("php://input");
		$AvailabilityData 					= json_decode($AvailabilityDataJSON, true);						
		$AvailabilityData					= $AvailabilityData['AvailabilityData'];
		$isAvailable						 = $AvailabilityData['availableToday'];
		$copy_previous					   = $AvailabilityData['copy_previous']?$AvailabilityData['copy_previous']:'no';
		$prev_availid 						= $AvailabilityData['prev_availid'];
		$old_radius 						  = $AvailabilityData['old_radius'];
		unset($AvailabilityData['availableToday']);
		unset($AvailabilityData['old_radius']);
		unset($AvailabilityData['old_location']);
		unset($AvailabilityData['prev_availid']);
		unset($AvailabilityData['copy_previous']);
			
		
		
		if(isset($AvailabilityData['timezone'])){
			$cron_start		  = $this->config->item('cron_start');
			
			$server_timezone  = date_default_timezone_get();
			
					
			$user_currenttime = new DateTime("now", new DateTimeZone($AvailabilityData['timezone']) );
			
			$set_availability_begin	= date($this->config->item('set_availability_begin'));
			$set_availability_begin	= new DateTime($set_availability_begin, new DateTimeZone($AvailabilityData['timezone']));
			
			$endTime 				   = date("h:i A", strtotime($this->config->item('set_availability_slot_end')));
			$config_avail_start		= date($this->config->item('set_availability_slot_end'));
			$config_avail_start		= new DateTime($config_avail_start , new DateTimeZone($AvailabilityData['timezone']));
			
			
			//$cron_time     = date('Y-m-d H:i:s', strtotime($config_avail_start->format('Y-m-d H:i:s')) - ($cron_start * 60 * 60));
			//$cron_time	   = new DateTime($cron_time , new DateTimeZone($AvailabilityData['timezone']) );
			
			$match_time_to		= date($AvailabilityData['match_time_to']);
			$match_time_to		= new DateTime($match_time_to , new DateTimeZone($AvailabilityData['timezone']));
			
			$cron_time     = date('Y-m-d H:i:s', strtotime($config_avail_start->format('Y-m-d H:i:s')) - ($cron_start * 60 * 60) - (15*60));			
			$cron_time	   = new DateTime($cron_time , new DateTimeZone($AvailabilityData['timezone']) );
			
			$userPoints = $this->user_model->getTotalPoints($AvailabilityData['member_id']);
			$availability_min_point = $this->config->item('availability_min_point')?$this->config->item('availability_min_point'):5;
			
			if($userPoints < $availability_min_point){
				$message = $this->config->item('MinimumPointMessage');
				$response['status'] 	= 'cannotset';
				$response['message'] 	= $message;
				echo json_encode($response);
				exit;
			}
			elseif($user_currenttime >= $cron_time  && $user_currenttime <= $config_avail_start)
			{
				$message = sprintf($this->config->item('CannotSetAvailabilityMassage1'), $endTime);
				$response['status'] 	= 'cannotset';
				$response['message'] 	= $message;
				echo json_encode($response);
				exit;
			}
			elseif($user_currenttime < $cron_time){
				
				$checkToday 	 = $this->user_model->checkTodaysMeeting($AvailabilityData['member_id'], $date );
				if($checkToday){
					$response['status'] 	= 'cannotset';
					$response['message'] 	= $this->config->item('AlreadySetAvailabilityMassage');
					echo json_encode($response);
					exit;
				}
				// availability time creation
				$AvailabilityData['created_time']		= date('Y-m-d H:i:s');
				$AvailabilityData['match_time_to']		= date('Y-m-d').' '.$AvailabilityData['match_time_to'];
				$AvailabilityData['match_time_from']	= date('Y-m-d').' '.$AvailabilityData['match_time_from'];				
				$istomorrow = false;	
				$date								   = date('Y-m-d');	
				$notification_start_at = $this->config->item('notification_start_at')?$this->config->item('notification_start_at'):'09:00:00';
				$notification_start = date('Y-m-d').' '.$notification_start_at;
			
				
			}
			elseif($user_currenttime > $config_avail_start){
				
				$AvailabilityData['created_time']	 = date('Y-m-d H:i:s');
				$AvailabilityData['match_time_to']	= date('Y-m-d', strtotime( date('Y-m-d') . ' + 1 day')).' '.$AvailabilityData['match_time_to'];
				$AvailabilityData['match_time_from']  = date('Y-m-d', strtotime( date('Y-m-d') . ' + 1 day')).' '.$AvailabilityData['match_time_from'];
				$istomorrow = true;
				$date			   =  date('Y-m-d', strtotime( date('Y-m-d') . ' + 1 day'));
				$notification_start_at = $this->config->item('notification_start_at')?$this->config->item('notification_start_at'):'09:00:00';
				$notification_start = date('Y-m-d', strtotime( date('Y-m-d') . ' + 1 day')).' '.$notification_start_at;
				
			}
			
			$AvailabilityData['match_time_from']      = $this->convertTime($AvailabilityData['timezone'],$AvailabilityData['match_time_from']);
			$AvailabilityData['match_time_to']	    = $this->convertTime($AvailabilityData['timezone'],$AvailabilityData['match_time_to']);
			$AvailabilityData['notification_start']   = $this->convertTime($AvailabilityData['timezone'],$notification_start);
			
			$response							= array();
			$existing		= $this->user_model->get_fields('match_log_master','*',"member_id = '".$AvailabilityData['member_id']."' AND DATE_FORMAT(match_time_from,'%Y-%m-%d')='".$date."'");
			
			$this->user_model->updatePrevLatLon($AvailabilityData);
			//var_dump($existing);
			#######mesage #######
			if($istomorrow){
				$messageI = $this->config->item('SaveAvailabilityTomorrowMessage');
			}
			else{
				$messageI = $this->config->item('SaveAvailabilityMessage');
			}
			
			
			if(!empty($existing))
			{
				$this->load->model('mobile/venue_model');
				$selected_venues = $this->venue_model->getMatchVenueDetails($existing[0]['match_logid']);
				if($result['result'] = $this->availability_model->updateAvailabilityDetail($existing[0]['match_logid'],$AvailabilityData))
					{
						if(sizeof($selected_venues) == 0 ){
							$response['result'] = $existing[0]['match_logid'];
							$response['status'] 			= 'addvenues';
							$response['message'] 			= $messageI;
							$response['selected_venues']	= $selected_venues;
						}
						else{
							$response['result'] = $existing[0]['match_logid'];
							$response['status'] 			= 'ok';
							$response['message'] 			= $messageI;
							$response['selected_venues']	= $selected_venues;
						}
					}
				else 
					{
						$response['status'] 	= 'error';
						$response['message'] 	= 'Error: some thing wrong happened!. Please try later.';	
					}			
			}
			else
			{
				
			
				if($copy_previous == 'yes' && $prev_availid > 0){
						$prev_venues = $this->availability_model->getMatchVenues($prev_availid );
						
						
						
						$AvailabilityData['active'] = 'Y';
						$match_logid =  $this->availability_model->insert($AvailabilityData);
						
						$venupdate =  $this->availability_model->insertVenues($prev_venues,$match_logid);
							
						if(sizeof($venupdate) > 0 ){
							$response['result'] = $match_logid;				
							$response['status'] 	= 'completed';
							$response['message'] 	= $messageI;
							insertPoint(array('id' => 7, 'member_id' => $AvailabilityData['member_id']),$response['result']);
						}
						else{
							$response['result'] = $match_logid;				
							$response['status'] 	= 'addvenues';
							$response['message'] 	= $messageI;
						}
						
				}
				else{
					$match_logid = $this->availability_model->insert($AvailabilityData);
					$response['result'] = $match_logid;				
					$response['status'] 	= 'ok';
					$response['message'] 	= $messageI;
					
				}
				
				if(!$match_logid){
						$response['status'] 	= 'error';
						$response['message'] 	= 'Error: some thing wrong happened!. Please try later.';	
				}
			}
		
		}
		else{
			$response['status'] 	= 'error';
			$response['message'] 	= 'Error: some thing wrong happened!. Please try later.';
		}
		echo json_encode($response); exit();
	}
	function getAvailability(){
		
		$profileIdJSON 				= file_get_contents("php://input");
		$profileid 					= json_decode($profileIdJSON, true);
		$profileid					= $profileid['userId'];
		$response					 = array();
		$time_range_slots			 = array();
		$time_range				   = array();
		if(date("H") > 14){
			$date					 = date('Y-m-d', strtotime("tomorrow"));
		}
		else{
			$date					 = date('Y-m-d');
		}
		
		$time_range_slots['0.00']		= date('H:i:s', strtotime($this->config->item('set_availability_slot_begin')));
		$time_range_slots['0.50']		= date('H:i:s', strtotime($this->config->item('set_availability_slot_begin')) + ( 60 * 30 ));
		$time_range_slots['1.00']		= date('H:i:s', strtotime($this->config->item('set_availability_slot_begin')) + ( 60 * 30 * 2));
		$time_range_slots['1.50']		= date('H:i:s', strtotime($this->config->item('set_availability_slot_begin')) + ( 60 * 30 * 3));
		$time_range_slots['2.00']		= date('H:i:s', strtotime($this->config->item('set_availability_slot_begin')) + ( 60 * 30 * 4));
		$time_range_slots['2.50']		= date('H:i:s', strtotime($this->config->item('set_availability_slot_begin')) + ( 60 * 30 * 5));
		$time_range_slots['3.00']		= date('H:i:s', strtotime($this->config->item('set_availability_slot_begin')) + ( 60 * 30 * 6));
		
		$time_range_flip   = array_flip ( $time_range_slots );		
		$time_range		= array_merge( $time_range_slots, $time_range_flip );
		$prev_availid 	  = $this->availability_model->getUserPrevLogID($profileid);
		//$nomatchId 		 = $this->availability_model->getNoMatchLog($profileid, $date);
		$result 			= $this->availability_model->getAvailabilityForApp($profileid,$date,0);
			
			if($result)
			{
				$response = array('status' => 'ok', 'message' => 'Date Fetched','prev_availid'=>$prev_availid);
				
					if(empty($result['match_logid']) && $prev_availid > 0 ){
						$prev_availability = $this->availability_model->getAvailabilityDetail($profileid, $prev_availid);
						
						$prev_availability['match_time_from'] = $this->convertTime(date_default_timezone_get(),$prev_availability['match_time_from'],$prev_availability['timezone']);
						$prev_availability['match_time_to']   = $this->convertTime(date_default_timezone_get(),$prev_availability['match_time_to'],$prev_availability['timezone']);
						$prev_availability['is_previous']   = true;
						/*$this->isItPrevious(date_default_timezone_get(),$prev_availability['match_time_to'],$prev_availability['timezone']);*/
						$response['result']	= $prev_availability;
						
					}
					else{
						$result['match_time_from'] = $this->convertTime(date_default_timezone_get(),$result['match_time_from'],$result['timezone']);
						$result['match_time_to']   = $this->convertTime(date_default_timezone_get(),$result['match_time_to'],$result['timezone']);
						
						$response['result']	= $result;
					}
				/*if($nomatchId > 0 ) {
					$response['nomatch'] =true;
					$this->availability_model->update_nomatch_log_read($nomatchId);
					$response['message'] = $this->config->item('NoMatchPushNotificationMessage');
				}*/
				
			}
			else
			{
				$response = array('status' => 'error', 'message' => 'Database Error');
			}
			
			$result['time_range']		 = json_encode($time_range);	
		
		
		
		//####### write Log ###########
			//$logString = json_encode($response);				
			//$myFile = '/home/newagesme/public_html/lunch_matcher/log/getAvailability_'.$profileid.'-'.time().'.txt';
			//$fh = fopen($myFile, 'w');
			//fwrite($fh, $logString);
			//fclose($fh);
		
		//$response['myFile'] = $myFile;
		######################
		
		echo json_encode($response); exit();
	}
	function convert($string,$format='24')
	{
		if($format == '24'):
			$time  = date("g:i A", strtotime($string)); //24 to 12
		else:
			$time  = date("H:i", strtotime($string));  //12 to 24
		endif;
		
		return $time;
	}
	function insertPointsForUser()
	{
		$postDataJSON 		= file_get_contents("php://input");
		$postData 			= json_decode($postDataJSON, true);
		$member_id			= $postData['userId'];
		$point_id			= $postData['pointId'];
		$ref_id			  = $postData['ref_id'];
		insertPoint(array('id' => $point_id, 'member_id' => $member_id),$ref_id);
	
	}	
	
	function insertPointsToDBForNoShow($point_id,$member_id,$log_id)
	{
		$no_sowuser =  $this->availability_model->getNoShowUser($log_id,$member_id);
		$response	= array();
		if($result = insertPoint(array('id' => $point_id, 'member_id' => $member_id), $log_id))
		{
			$response = array('status' => 'ok', 'message' => 'Point Inserted', 'result' => $result);	
		}
		else
		{
			$response = array('status' => 'error', 'message' => 'Database Error', 'result' => $result);	
		}
		echo json_encode($response); exit();
	}
	function insertPointsToDB($point_id,$member_id,$ref_id=0)
	{
		$response	= array();
		if($result = insertPoint(array('id' => $point_id, 'member_id' => $member_id)))
		{
			$response = array('status' => 'ok', 'message' => 'Point Inserted', 'result' => $result);	
		}
		else
		{
			$response = array('status' => 'error', 'message' => 'Database Error', 'result' => $result);	
		}
		echo json_encode($response); exit();
	}
	function insertPointsToDB2($point_id,$member_id)
	{
		$response	= array();
		if($result = insertPoint(array('id' => $point_id, 'member_id' => $member_id)))
		{
			$response = array('status' => 'ok', 'message' => 'Point Inserted', 'result' => $result);	
		}
		else
		{
			$response = array('status' => 'error', 'message' => 'Database Error', 'result' => $result);	
		}
		return $response;
	}
	
	function noMatches()
	{
		$postDataJSON 				= file_get_contents("php://input");
		$postData 					= json_decode($postDataJSON, true);
		$member_id					= $postData['userId'];
		
		$log  = $this->availability_model->update_nomatch_log_read($member_id);
		
		$response = array('status' => 'ok', 'message' => 'Read');	
		echo json_encode($response); exit();
	}
	function getVenues(){
		$postDataJSON 				= file_get_contents("php://input");
		$postData 					= json_decode($postDataJSON, true);
		$member_id				   = $postData['userId'];
		
		$latlon 	= 	$postData['venuesData']['lat'].','.$postData['venuesData']['lon'];
		$radius 	= 	$postData['venuesData']['radius'] * 1000; 
		$api_key = $this->config->item('google_place_api_key');
		
		$FavVenues = $this->user_model->getFavouriteVenueIds($member_id);
		$FinalVenues = array();
		//echo "<pre>"; print_r($FavVenues); echo "</pre>"; exit;
		
		/*$url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?keyword=restaurant&location='.$latlon.'&radius='.
										$radius.'&types=restaurant&rankBy=distance&key='.$api_key;*/
										
		$url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?types=restaurant&rankby=distance&location='.
							$latlon.'&key='.$api_key;
										
		if($postData['nextPageToken'] != '')
			$url = $url.'&pagetoken='.$postData['nextPageToken'];
						
		$venues 	 =	file_get_contents($url);
		$venues 	 = json_decode($venues,true);	
		
		if($venues ['status'] == 'OK'){
			if(sizeof($venues['results']) > 0 ) {
				for($i=0;$i<count($venues['results']); $i++){
					$FinalVenues[] = $venues['results'][$i]['place_id'];
					if($FavVenues && in_array($venues['results'][$i]['place_id'],$FavVenues)){
						$venues['results'][$i]['favourite'] = true;
					}
					else{
						$venues['results'][$i]['favourite'] = false;
					}
				}
			}
			$blockedVenues = $this->user_model->checkBlockedVenues($FinalVenues);
			$venuesImages = $this->user_model->getVenuesImage($FinalVenues);
			$response['status']  		   = 'OK';
			$response['results'] 		  = $venues['results'];
			$response['next_page_token']  = $venues['next_page_token'];
			$response['blockedVenues'] 	= $blockedVenues;
			$response['venuesImages'] 	 = $venuesImages;
		}
		else{
			$response = array('status' => 'fail', 'message' => 'There are restaurants found.', 'results' => $venues);
		}
		
		//echo "<pre>"; print_r($venuesImages ); echo "</pre>"; exit;
		
		
		echo json_encode($response); exit();	
	}
	function getFavVenues()
	{
		$postDataJSON 				= file_get_contents("php://input");
		$postData 					= json_decode($postDataJSON, true);
		$member_id					= $postData['userId'];
		$result 					= array();
		if($tempresult = $this->user_model->getUserFavouriteVenues($member_id))
		{
			foreach($tempresult as $value)
			{
				
				$value['vicinity'] 	= $value['address'];
				$value['photos'] 	= array('0' => array('photo_reference' => $value['photo_key'] ) );
				$value['place_id'] 	= $value['venue_id'];
				if( $value['name'] == NULL )
				{
					$api_key = getConfigValue('google_place_api_key');
					$google_api_url ="https://maps.googleapis.com/maps/api/place/details/json?placeid=".$value['venue_id']."&key=".$api_key;				
					$venueDetails =	file_get_contents($google_api_url);
					$venueDetails = json_decode($venueDetails,true);		
					$value['name']		 	= $venueDetails['result']['name'];
					$value['place_id'] 		= $venueDetails['result']['place_id'];
					$value['name'] 			= $venueDetails['result']['name'];
					$value['vicinity'] 		= $venueDetails['result']['vicinity'];
					$value['latitude'] 		= $venueDetails['result']['geometry']['location']['lat'];
					$value['longitude'] 	= $venueDetails['result']['geometry']['location']['lng'];
					//$value['photo_key'] 	= $venueDetails['photos'][0]['photo_reference'];
					$value['photos'] = array('0' => array('photo_reference' => $venueDetails['result']['photos'][0]['photo_reference'] ) );
					$value['rating'] 		= $venueDetails['rating'];
				}
			$result[] = $value;			
			}
			$response = array('status' => 'ok', 'message' => 'Data Fetched', 'result' => $result);
		}
		else
		{
			$response = array('status' => 'error', 'message' => 'Database Error', 'result' => $result);	
		}
		echo json_encode($response); exit();	
	}
	function saveFav(){
		$postDataJSON 		= file_get_contents("php://input");
		$postData 			= json_decode($postDataJSON, true);
		$venue_id				= $postData['venue_id'];
		$member_id			= $postData['member_id'];
		$favourite			= $postData['favourite'];
		//echo "<pre>"; print_r($postData); echo "</pre>"; exit;
		if($favourite == 'true')
		{
			$favData					= array();		
			$favData['member_id']	 	= $member_id;
			$favData['venue_id'] 		= $venue_id;
			$result		= $this->user_model->insert_fields('member_favourite_venues',$favData);
			$response = array('status' => 'ok', 'message' => 'Fav added', 'result' => $result);
		}
		else
		{
			$result		= $this->user_model->delete_row('member_favourite_venues',"member_id = $member_id AND venue_id = '".$venue_id."'");
			$response = array('status' => 'ok', 'message' => 'Fav removed', 'result' => $result);	
		}
		echo json_encode($response); exit();
	}
	function saveVenues()
	{
		$this->load->model('mobile/venue_model');
		$venuesInfoJSON 				= file_get_contents("php://input");
		$venuesInfo 					= json_decode($venuesInfoJSON, true);
		$member_id						= $venuesInfo['memberId'];
		$availabilityId					= $venuesInfo['availabilityId'];
		$venuesInfo						= $venuesInfo['venueDetails'];		
		$response 					= array();		
		$venuesData 				= array();
		$venuesData['match_logid'] 	= $availabilityId;		
		$favData					= array();		
		$favData['member_id']		= $member_id;
		$this->venue_model->delete_existing_venues($availabilityId);
		/*$favourites = $this->venue_model->getFavsFlat($member_id);*/
			if(sizeof($venuesInfo) > 0 ){
				foreach($venuesInfo as $venue){
					if($venue['selected'] == 'true' || $venue['favourite'] == 'true'){
						$venueDetails 	  			= array();
						$venueDetails['venue_id'] 	= $venue['place_id'];
						$venueDetails['name'] 		= $venue['name'];
						$venueDetails['address'] 	= $venue['vicinity'];
						$venueDetails['latitude'] 	= $venue['geometry']['location']['lat'];
						$venueDetails['longitude'] 	= $venue['geometry']['location']['lng'];
						$venueDetails['photo_key'] 	= $venue['photos'][0]['photo_reference'];
						$venueDetails['rating'] 	   = $venue['rating'];
						$this->venue_model->insert_unique($venueDetails);
					}			
					
					if($venue['selected'] == 'true'){					
						$venuesData['venue_id'] = $venue['place_id'];
						$ids[]				  = $this->user_model->insert_fields('match_log_venues',$venuesData);
					}
					/*if($venue['favourite'] == 'true')
						{
							if (!in_array($venue['place_id'], $favourites)) {
									$favData['venue_id'] = $venue['place_id'];
									$result				 = $this->user_model->insert_fields('member_favourite_venues',$favData);
							}
						}*/
				}
			}
			if(sizeof($ids) > 0 ){	
				insertPoint(array('id' => 7, 'member_id' => $member_id),$availabilityId);
				$this->availability_model->updateMemberPrevAvailability($member_id, $availabilityId);
				$this->availability_model->changeAvailabilityStatus($availabilityId);				
				$response = array('status' => 'ok', 'message' => $this->config->item('SaveAvailabilityMessage'), 
								 'venuesInfo'=>$venuesInfo);
			}
			else{
				$response = array('status' => 'error', 'message' => $this->config->item('SaveAvailabilityErrorMessage'), 
							'venuesInfo'=>$venuesInfo);
			}
	 		
	
	 	echo json_encode($response); exit();
	}
	
	public function convertTime( $user_time_zone='',$user_time='',$to_time_zone='')
	{
			$current_time_zone = date_default_timezone_get();
			
			//$user_time_zone	= 'America/Mexico_City';
			$to_time_zone 	= ($to_time_zone)?$to_time_zone:date_default_timezone_get();
			$user_time_zone = ($user_time_zone)?$user_time_zone:date_default_timezone_get();
			
			//$triggerOn = '04/01/2013 03:08 PM';

			//echo $triggerOn; // echoes 04/01/2013 03:08 PM
			if($user_time_zone){
			$schedule_date = new DateTime($user_time, new DateTimeZone($user_time_zone) );
			$schedule_date->setTimeZone(new DateTimeZone($to_time_zone));
			$user_time =  $schedule_date->format('Y-m-d H:i:s');
			}
		return $user_time;
	}
	public function isItPrevious( $user_time_zone='',$user_time='',$to_time_zone='')
	{
			$current_time_zone = date_default_timezone_get();
			
			//$user_time_zone	= 'America/Mexico_City';
			$to_time_zone 	= ($to_time_zone)?$to_time_zone:date_default_timezone_get();
			$user_time_zone = ($user_time_zone)?$user_time_zone:date_default_timezone_get();
			
			//$triggerOn = '04/01/2013 03:08 PM';

			//echo $triggerOn; // echoes 04/01/2013 03:08 PM
			if($user_time_zone){
				$schedule_date = new DateTime($user_time, new DateTimeZone($user_time_zone) );
				$schedule_date->setTimeZone(new DateTimeZone($to_time_zone));
				$user_time =  $schedule_date->format('Y-m-d');
			}
			if($user_time == date('Y-m-d')) 
				return false;
			else
				return true;
		
	}
	public function convertTimeToSiteFormat( $user_time_zone='',$user_time='',$to_time_zone='')
	{
			$current_time_zone = date_default_timezone_get();
			
			//$user_time_zone	= 'America/Mexico_City';
			$to_time_zone 	= ($to_time_zone)?$to_time_zone:date_default_timezone_get();
			$user_time_zone = ($user_time_zone)?$user_time_zone:date_default_timezone_get();
			
			//$triggerOn = '04/01/2013 03:08 PM';

			//echo $triggerOn; // echoes 04/01/2013 03:08 PM
			if($user_time_zone){
			$schedule_date = new DateTime($user_time, new DateTimeZone($user_time_zone) );
			$schedule_date->setTimeZone(new DateTimeZone($to_time_zone));
			$user_time =  $schedule_date->format(getConfigValue('time_format'));
			}
		return $user_time;
	}
	
	function getLogDetailsFromTemp(){
			$this->load->model('mobile/cron_model');
			$postDataJSON 	   = file_get_contents("php://input");
			$postData 		   = json_decode($postDataJSON, true);
			$match_logid		= $postData['match_logid'];
			$user_id			= $postData['user_id'];
			$tempLog  			= $this->cron_model->getRecentMatchFromLogid($match_logid);
			$result			 = array(); 
			//echo "<pre>"; print_r($tempLog ); echo "</pre>"; exit;
			if($tempLog){
				$logTimeDiff 		      = strtotime(date('Y-m-d H:i:s')) - strtotime($tempLog['created_time']);
				
				$logTimeDiffMin     	   = round(abs($logTimeDiff) / 60,0);
				$result['log_id'] 		 = $tempLog['log_id'];
				$result['logTimeDiffMin'] = $logTimeDiffMin;
				if($logTimeDiffMin > 15){
					$nomatchId = $this->cron_model->getNoMatchLog($user_id, $match_logid);
					if($nomatchId > 0)
						$result['no_matches'] = true;
					$this->cron_model->update_nomatch_log_read($nomatchId);
				}
				
				if($match_logid == $tempLog['from_match_logid']){
					$result['matchuser_id'] 		= $tempLog['to_match_logid'];
					$result['matchuser_status']    = $tempLog['to_match_status'];
					$result['my_status']		   = $tempLog['from_match_status'];
					$result['notfic_time']		 = $tempLog['created_time'];	
				}
				else{
					$result['matchuser_id'] 		= $tempLog['from_match_logid'];
					$result['matchuser_status']    = $tempLog['from_match_status'];
					$result['my_status']		   = $tempLog['to_match_status'];
					$result['notfic_time']		 = $tempLog['created_time'];
				}
				
				$response = array('status' => 'ok', 'message' => 'Data Fetched', 'result' => $result);	
			}
			else{
					$nomatchId = $this->cron_model->getNoMatchLog($user_id, $match_logid);
					if($nomatchId > 0)
						$result['no_matches'] = true;
					$response = array('status' => 'ok', 'message' => 'Set availability');	
					$this->cron_model->update_nomatch_log_read($nomatchId);
			}
			
			
			echo json_encode($response);
	}
	public function getShare(){
		$dataJSON 		     = file_get_contents("php://input");
		$data 			     = json_decode($dataJSON, true);
		$restaurant 	       = $this->user_model->getRestaurantName($data['ref_id']);
		
		
		$share_description 	= $this->config->item('share_description');
		
		$share_description2 = sprintf($share_description, $restaurant->name?$restaurant->name:''); 
		
		$response = array('status' => 'success', 'shareDesc' => $share_description2
							, 'shareURL' => $this->config->item('share_url'));
							
		if($this->user_model->checkPointAdded($data['ref_id'],$data['point_id'],$data['member_id'])){
			$response['status'] = 'shared';
		}
		
		echo json_encode($response);
		
	}
	protected function _initNotifications($user,$payload){ 	
			$this->load->model('general_model'); 
			if($user->device_platform == 'Android' || $user->device_platform == 'android'){
			if($user->device_token){  
					$reg_id = array(0=>$user->device_token);				
					$this->general_model->sendPushNotification_ANDROID($reg_id,$payload);
				}
			}else if($user->device_token){ 			
				$this->general_model->sendPushNotification_IOS($payload['title'],$user->device_token,$payload);		
			}
		
		}
	public function getPlace(){
		
		$api_key = $this->config->item('google_place_api_key');
		/*$url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?keyword=restaurant&location='.$latlon.'&radius='.
										$radius.'&types=restaurant&rankBy=distance&key='.$api_key;*/
										
		$url = 'https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJa7EyH5n9mzkR54uXCYm6zJM&key='.$api_key ;
		echo $url; exit;
																		
		if($postData['nextPageToken'] != '')
			$url = $url.'&pagetoken='.$postData['nextPageToken'];
						
		$venues 	 =	file_get_contents($url);
		$venues 	 = json_decode($venues,true);
		echo "<pre>"; print_r($venues); echo "</pre>"; exit;	
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */