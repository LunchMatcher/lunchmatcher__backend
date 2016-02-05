<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class User_model extends MY_Model {  
	public $_table = 'member_master';
	
	public function setTable($table){
		$this->_table = $table;
	}
	
	public function getHistory($id)
	{
		$sql="SELECT ta.*,mm.formatted_name,mm.picture_url,mf.rating_val, mp.location, 
				DATE_FORMAT(schedule_timefrom, '%d %b, %Y') matchdate 
				,(SELECT company_name FROM member_current_positions WHERE member_id =  mm.member_id LIMIT 1 ) as company
				,(SELECT position_title FROM member_current_positions WHERE member_id =  mm.member_id LIMIT 1 ) as headline
				, vd.name, vd.address, vd.photo_key 
				FROM (SELECT *, to_user as matchid FROM venue_sceduled_log WHERE from_user = $id 
				UNION
				SELECT *, from_user as matchid FROM venue_sceduled_log WHERE to_user = $id) ta 
				LEFT JOIN
				member_master mm ON ta.matchid = mm.member_id
				LEFT JOIN
				member_profile mp ON mp.member_id = ta.matchid
				LEFT JOIN
				member_feedbacks mf ON mf.scheduled_log_id = ta.log_id 
				LEFT JOIN
				venue_details vd ON ta.venue_id = vd.venue_id  GROUP BY log_id DESC
			  ";
		
		$result =  $this->db->query($sql);
		return $result->result_array();
	}
	public function getTotalPoints($member_id){
		$sql = "select SUM(points) as total_points
		FROM member_points_log 
		where member_id='$member_id'";
		$result =  $this->db->query($sql);
		$results =  $result->row_array();	
		//echo $str = $this->db->last_query();
		//print_r($results);exit;
		return $results['total_points']?$results['total_points']:0;
	}
	
	
	public function getPoints($member_id){
		$sql = "select member_points_log.*,DATE_FORMAT(member_points_log.action_date, '%M %d, %Y') as action_date, 
		m1.title, m1.point_type, mm.picture_url
		FROM member_points_log 
		INNER JOIN point_master m1 on m1.point_id = member_points_log.point_id
		LEFT JOIN member_master mm ON (mm.member_id = member_points_log.ref_id)
		where member_points_log.member_id='$member_id' 
		ORDER BY member_points_log.action_date DESC, member_points_log.id DESC";
		$result =  $this->db->query($sql);
		$results =  $result->result_array();	
		//echo $str = $this->db->last_query();
		//print_r($results);exit;
		return $results;
			
	}	
	 public function purchasePoint($arr)
	{
		$this->db->insert('member_points_log', $arr);
		return $this->db->insert_id();	
	}
	
	public function getUserDetailsFromDeviceId($id)
	{
		$this->db->select('member_master.*,member_profile.member_dob,member_profile.contact_number, 
							member_profile.location,member_profile.country_code,member_profile.industry');
		$this->db->from('member_master');
		$this->db->where('member_master.device_id', $id); 
		$this->db->join('member_profile', 'member_master.member_id = member_profile.member_id','left');
		$result = $this->db->get();
		$resu=$result->result_object();
		return $resu[0];		
	}
	
	public function updateDeviceDetails($profileid,$deviceDetails)
	{
		$this->removeExistingToken($deviceDetails['device_token']);
		
		$sql = "UPDATE member_master SET device_token='".$deviceDetails['device_token']."',device_platform='".$deviceDetails['device_platform']."' where member_id = ".$profileid;
		$result =  $this->db->query($sql);
		
		return $result;
	}
	
	public function updateDeviceDetailsbyDeviceId($deviceDetails)
	{
		$this->removeExistingToken($deviceDetails['device_token']);
		
		$sql = "UPDATE member_master SET device_token='".$deviceDetails['deviceToken']."',device_platform='".$deviceDetails['devicePlatform']."' where device_id = '".$deviceDetails['deviceId']."'";
		$result =  $this->db->query($sql);
		
		return $result;
	}
	public function getVenueDet($venue_id=''){
		
		/*$sql = "SELECT *, IF(image IS NULL,'',CONCAT('".site_url('uploads/venue')."/',image,'?t=".time()."')) as image
				FROM venue_details
				WHERE venue_id = '$venue_id'";*/
		
		$sql = "SELECT *, IF(image IS NULL,'',CONCAT('".site_url('uploads/venue')."/',image)) as image
				FROM venue_details
				WHERE venue_id = '$venue_id'";
				
		$result =  $this->db->query($sql);
		$row =  $result->row();
		return $row;
	}
	
	public function getMeetingDetails($matcherId, $memberId, $logId)
	{
		$this->db->_protect_identifiers=false;
		
		$sql = "
				SELECT vsl.log_id,vsl.from_log_id  ,vsl.to_log_id ,vsl.from_user ,vsl.to_user ,vsl.venue_id
							, vsl.schedule_timefrom as time_from
							,DATE_SUB(vsl.schedule_timeto,INTERVAL 1 HOUR) as start_time
							,vsl.schedule_timeto as time_to ,vsl.from_user_status, vsl.to_user_status 
							, IF(vsl.from_user = $matcherId, mm1.formatted_name, mm2.formatted_name ) as matcherName
							, IF(vsl.from_user = $matcherId, mm1.picture_url, mm2.picture_url ) as matcherPicture
							, IF(vsl.from_user = $matcherId, mp1.contact_number, mp2.contact_number ) as matcherPhone
							, IF(vsl.from_user = $matcherId, mp1.location, mp2.location ) as matcherLocation
							, IF(vsl.from_user = $memberId, mm1.formatted_name, mm2.formatted_name ) as myName
							, IF(vsl.from_user = $memberId, mlm1.timezone, mlm2.timezone ) as myTimezone
							, IF(vsl.from_user = $memberId, vsl.from_user_status, vsl.to_user_status ) as myStatus
							, (SELECT company_name FROM member_current_positions WHERE member_id = IF(vsl.from_user = $matcherId, vsl.from_user, vsl.to_user ) LIMIT 1 ) as matcherCompany
							, (SELECT position_title FROM member_current_positions WHERE member_id = IF(vsl.from_user = $matcherId, vsl.from_user, vsl.to_user ) LIMIT 1 ) as matcherHeadline
					
					FROM venue_sceduled_log vsl 
					LEFT JOIN match_log_master mlm1 ON (mlm1.match_logid = vsl.from_log_id)
					LEFT JOIN match_log_master mlm2 ON (mlm2.match_logid = vsl.to_log_id)
					LEFT JOIN member_master mm1 ON (mm1.member_id = vsl.from_user )
					LEFT JOIN member_master mm2 ON (mm2.member_id = vsl.to_user)
					
					LEFT JOIN member_profile mp1 ON (mp1.member_id = vsl.from_user )
					LEFT JOIN member_profile mp2 ON (mp2.member_id = vsl.to_user)
					WHERE `vsl`.`log_id` = $logId ";
	
	
		$result =  $this->db->query($sql);
		$results =  $result->result_object();
		return $results[0];
			
	}
	
   public function getMatchDetails($id,$log_id)
	{
			
		$sql = "SELECT mm.member_id,mm.formatted_name 
					,mm.picture_url,mp.contact_number,mp.location,mp.country_code,mp.industry
					, DATE_SUB(LEAST(mlm.match_time_to,mlm2.match_time_to),INTERVAL 1 HOUR) as match_time_from
					, LEAST(mlm.match_time_to,mlm2.match_time_to) as match_time_to, mlm.timezone, SUM(mpl.points) as score
					,(SELECT company_name FROM member_current_positions WHERE member_id =  mm.member_id LIMIT 1 ) as company
					,(SELECT position_title FROM member_current_positions WHERE member_id =  mm.member_id LIMIT 1 ) as headline	
				 FROM temp_matching_log tml
				 LEFT JOIN match_log_master mlm ON (mlm.match_logid = tml.from_match_logid)
				 LEFT JOIN match_log_master mlm2 ON (mlm2.match_logid = tml.to_match_logid)
				 LEFT JOIN member_master mm ON(mm.member_id = IF(tml.from_match_logid = $id, mlm.member_id, mlm2.member_id))
				 LEFT JOIN member_profile mp ON (mp.member_id = mm.member_id)
				 LEFT JOIN member_points_log mpl ON (mpl.member_id = mm.member_id)
				 WHERE tml.log_id = $log_id ";
				 
	
		$result =  $this->db->query($sql);
		$results =  $result->result_object();
		return $results[0];
	}
	
	public function get_field($table_name = '',$select_field = '',$where_string = '')
	{
		if(!$table_name || !$select_field)
						return false;
		$this->db->select($field_name);
		$this->db->from($table_name);
		
		if($where_string)
 			 $this->db->where($where_string, NULL, FALSE);
		/*if($where_field && $where_value)		
			$this->db->where($where_field, $where_value); 	*/
		$result = $this->db->get();
		//echo $this->db->last_query();exit;
		return $result->row_array();
	}
	
	public function insert_fields($table_name,$arr)
	{
		$this->db->insert($table_name, $arr);
		//return $this->db->last_query(); 
		return $this->db->insert_id();
	}
	public function getUserDetails($id)
	{
		$this->db->select('member_master.*,member_profile.member_dob,member_profile.contact_number,member_profile.location,member_profile.country_code,member_profile.lunch_area,member_profile.industry, SUM(points) as total_score', false);
		$this->db->from('member_master');
		$this->db->where('member_master.member_id', $id); 
		$this->db->join('member_profile', 'member_master.member_id = member_profile.member_id','left');
		$this->db->join('member_points_log', 'member_master.member_id = member_points_log.member_id','left');
		$result = $this->db->get();
		$resu=$result->result_object();
		return $resu[0];		
	}
	public function get_preference($id){

		$sql = 'SELECT mm.gender,mm.email, mp.contact_number,mr.*, mm.member_id, mm.field_required FROM member_master mm 
					LEFT JOIN member_profile mp ON mm.member_id = mp.member_id
					LEFT JOIN member_preferences mr ON mm.member_id = mr.member_id
				WHERE mm.member_id = '.$id;
	   
		$result =  $this->db->query($sql);
		$results =  $result->result_array();
		return $results[0];	
	}
	
	public function get_fields($table_name = '',$select_field = '',$where_string = '')
	{
		if(!$table_name || !$select_field)
						return false;
		$this->db->select($field_name);
		$this->db->from($table_name);
		
		if($where_string)
 			 $this->db->where($where_string, NULL, FALSE);
  
		/*if($where_field && $where_value)		
			$this->db->where($where_field, $where_value); 	*/
		$result = $this->db->get();
		return $result->result_array();
	}
	
	public function update_fields($table_name,$data){
		$member_id 			= $data['member_id'];
		unset($data['member_id']);
		$arr1 				= array_flip($this->getFieldNames($table_name));  		
		$arr 		= array_intersect_key($data, $arr1);
		
		$result = $this->db->update($table_name, $arr,array('member_id' => $member_id));
		return $result;
	}
	public function delete_row($table_name = '',$where_string = '')
	{
		if(!$table_name || !$where_string)
				return false;
		 $this->db->where($where_string, NULL, FALSE);
   		$result=$this->db->delete($table_name);
		/*if($where_field && $where_value)		
			$this->db->where($where_field, $where_value); 	*/
		return $result;
	}
	
	public function removeExistingToken($device_token)
	{
		$sql = "UPDATE member_master SET device_token='' AND device_platform='' where device_token = '".$device_token."'";
		$result =  $this->db->query($sql);
		return $result;
	}
	public function checkTodaysMeeting($member_id, $date)
	{
		$this->db->_protect_identifiers=false;
		
		$sql = "SELECT vsl.log_id
				 FROM match_log_master mlm
				 INNER JOIN venue_sceduled_log vsl ON ((mlm.match_logid = vsl.from_log_id OR mlm.match_logid = vsl.to_log_id))
				 WHERE DATE_FORMAT(mlm.created_time,'%Y-%m-%d')= '$date' AND member_id = $member_id";
	
		$result =  $this->db->query($sql);
		$results =  $result->result_object();
		return $results[0]->log_id;
			
	}
	
	public function updatePrevLatLon($data)
	{		
		$sql = "UPDATE member_master SET prev_lat='".$data['match_latitude']."',prev_lon='".$data['match_longitude']."' where member_id = ".$data['member_id'];
		$result =  $this->db->query($sql);		
		return $result;
	}
	
	public function getFavouriteVenueIds($member_id){
		$output =  array();
		if(!$member_id) return $output;
		
		$sql = "SELECT venue_id FROM member_favourite_venues
				WHERE member_id='$member_id'";
		$result =  $this->db->query($sql);
		$results =  $result->result_array();
		
		if($results){
			foreach($results as $result){
					$output[] = $result['venue_id'];
			}
		}
		return $output;
			
	}
	
	public function checkBlockedVenues($vienus){
		$output =  array();
		if(!$vienus) return $output;
		
		$sql = "SELECT venue_id FROM venue_details
				WHERE venue_id IN ('". implode("' , '" ,$vienus)." ') AND is_block = 'Y' ";
		$result =  $this->db->query($sql);
		$results =  $result->result_array();
		
		if($results){
			foreach($results as $result){
					$output[] = $result['venue_id'];
			}
		}
		return $output;
			
	}
	
	public function getVenuesImage($vienus){
		$output =  array();
		if(!$vienus) return $output;
		
		/*$sql = "SELECT id, venue_id, CONCAT('".site_url('uploads/venue')."/',image,'?t=".time()."') as image
				FROM venue_details
				WHERE venue_id IN ('". implode("' , '" ,$vienus)." ') AND image != ''";*/
		$sql = "SELECT id, venue_id, CONCAT('".site_url('uploads/venue')."/',image) as image
				FROM venue_details
				WHERE venue_id IN ('". implode("' , '" ,$vienus)." ') AND image != ''";
		
		$result =  $this->db->query($sql);
		$results =  $result->result_array();
		
		if($results){
			foreach($results as $result){
					$output[$result['venue_id']] = $result['image'];
			}
		}
		return $output;
			
	}
	
	public function getUserFavouriteVenues($member_id){
		$sql = "select *,mfv.venue_id as venue_id from member_favourite_venues mfv
		left join venue_details m1 on m1.venue_id = mfv.venue_id
		where mfv.member_id='$member_id'";
		$result =  $this->db->query($sql);
		$results =  $result->result_array();	
		//echo $str = $this->db->last_query();
		//print_r($results);exit;
		return $results;
			
	}
	
	
	public function getuser_with_mobile($mobile){
		$this->db->select("*");
		$this->db->from('member_master');
		$this->db->join('user_details','member_master.id=user_details.user_id');
		$this->db->where('member_master.mobile', $mobile);
		$query = $this->db->get();
		if($query->num_rows()){
			return $query->row();
		}
		return null;
		
	}
	
	function getFieldNames($table){
		return $this->db->list_fields($table);
	}

	function updateUser($data){
		$user_id 			= $data['id'];
		$arr1 				= array_flip($this->getFieldNames('member_master'));  		
		$member_master 		= array_intersect_key($data, $arr1);
		if($this->update($user_id,$member_master)){
			return $this->getUserData(array('member_master.id'=>$user_id));
		}
	}
	
	
	
	public function checkDeviceIdExists($id)
	{
		if(!$id) return false;
		
		$this->db->select('member_id,status,is_block');
		$this->db->from('member_master');
		$this->db->where('member_master.device_id', $id); 
		$result = $this->db->get();
		$resu=$result->result_object();
		return $resu[0]->member_id?$resu[0]->member_id:false;		
	}
	
	
	
	public function getMatcherDetails($logid, $matcherId,$memberId)
	{
		$this->db->_protect_identifiers=false;
		
		$sql = "SELECT vsl.schedule_timefrom
				, DATE_SUB(vsl.schedule_timeto,INTERVAL 1 HOUR) as start_time
				, vsl.schedule_timeto
				, vsl.venue_id
				, IF(vsl.from_user = $memberId, mlm1.timezone, mlm2.timezone ) as myTimezone
				, mm.member_id,  mm.formatted_name, mm.picture_url, 
		        IFNULL(vf.rating_val,0) as v_rating_val, vf.feed_back as v_feed_back ,
				
				SUM(mpl.points) as score, vd.name as venue_name,
				mf.no_show, mf.rating_val, mf.feed_back,mf.feed_id, 
				(SELECT company_name FROM member_current_positions WHERE member_id =  mm.member_id LIMIT 1 ) as company	
				,(SELECT position_title FROM member_current_positions WHERE member_id =  mm.member_id LIMIT 1 ) as headline
				FROM venue_sceduled_log vsl
				LEFT JOIN match_log_master mlm1 ON (mlm1.match_logid = vsl.from_log_id)
				LEFT JOIN match_log_master mlm2 ON (mlm2.match_logid = vsl.to_log_id)
				LEFT JOIN member_feedbacks mf ON (vsl.log_id = mf.scheduled_log_id AND mf.given_user = $memberId AND mf.member_id = $matcherId)
				LEFT JOIN member_master mm ON (mm.member_id = IF(vsl.from_user = $matcherId,vsl.from_user,vsl.to_user))
				LEFT JOIN member_points_log mpl ON (mpl.member_id = mm.member_id)
				LEFT JOIN venue_feedbacks vf ON (vf.scheduled_log_id  = vsl.log_id AND vf.given_user = $memberId)
				LEFT JOIN venue_details vd ON (vd.venue_id = vsl.venue_id)
				WHERE vsl.log_id = $logid";
		//echo $sql; exit;
	
		$result =  $this->db->query($sql);
		$row =  $result->row();
		return $row;
			
	}
	
	public function insertEnquiryProfileDetails($arr)
	{
	    $this->db->insert('member_profile', $arr);
		return $this->db->insert_id();	
		
	}		
	public function insertEnquiryDetails($arr)
	{
		$sql="SELECT * from member_master where email='".$arr['email']."'";
		$result =  $this->db->query($sql);
		$results =  $result->row_array();
		if(count($results)==0)
		{
			$this->db->insert('member_master', $arr);
			return $this->db->insert_id();	
		}else{
			return "error";
		}
	}
	
	public function checkPointAdded($ref_id,$point_id,$member_id){
		if(!$ref_id || !$point_id || !$member_id)
			return false;
			
		$sql="SELECT id num from member_points_log 
			 WHERE  point_id=$point_id AND ref_id = $ref_id AND member_id = $member_id ";
		$result =  $this->db->query($sql);
		$results =  $result->row();
		return count($results);
	}
	public function insertEnquiryDetailsLinked($arr)
	{
		//print_r($arr);exit;
		$sql="SELECT * from member_master where auth_id='".$arr['auth_id']."' or email='".$arr['email']."'";
		$result =  $this->db->query($sql);
		$results =  $result->row_array();
		if(count($results)==0)
		{
			$this->db->insert('member_master', $arr);
			return $this->db->insert_id();	
			return 0;
		}else{
			$this->db->where('auth_id', $arr['auth_id']);
			$this->db->update('member_master', $arr); 
			return "error";
		}
	}
	public function getRestaurantName($id)
	{
		if(!$id) return false;
		
		$sql="SELECT venue_details.*	FROM venue_details 
				INNER JOIN venue_sceduled_log ON (venue_sceduled_log.venue_id = venue_details.venue_id )
				WHERE venue_sceduled_log.log_id = $id";
	
		$result =  $this->db->query($sql);
		return $result->row();
	}
	
}