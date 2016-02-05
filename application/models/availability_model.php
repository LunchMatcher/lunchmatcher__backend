<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class Availability_model extends MY_Model { 
	public $_table = 'match_log_master';
	
	public function setTable($table){
		$this->_table = $table;
	}
	public function get_all_availability(){
		$this->db->select("*");
		$this->db->from("match_log_master");
		$this->db->join("member_master","member_master.member_id=match_log_master.member_id");
		$result = $this->db->get();
		return $result->result_array();
	}
	public function getMatchVenues($match_logid){
		$this->db->select("*");
		$this->db->from("match_log_venues");
		$this->db->where('match_logid', $match_logid); 
		$result = $this->db->get();
		return $result->result_array();
	}
	public function updateAvailability($id){
		$sql = "UPDATE match_log_master set active = 'Y' where match_logid = $id";
		$result =  $this->db->query($sql);
		return $result;
	}
	public function updateMemberPrevAvailability($member_id, $avlid){
		if(!$member_id) return false;
		$sql = "UPDATE member_master set prev_availid = $avlid where member_id = $member_id";
		$result =  $this->db->query($sql);
		return $result;
	}
	
	public function updateAvailabilityDetail($id, $data){
		$this->db->where('match_logid', $id);
		$result =  $this->db->update('match_log_master', $data); 
		return $result;
	}
	public function getMeetingsBydate($startdate,$enddate){
			$sql="SELECT mm1.member_id as f_member_id,
						 mm1.first_name as f_member_first_name, 
						 mm1.last_name as f_member_last_name,
						 mm1.picture_url as f_member_picture, 
						 mm2.member_id as t_member_id ,
						 mm2.first_name as t_member_first_name, 
						 mm2.last_name as t_member_last_name,
						 mm2.picture_url as t_member_picture, 
						 venue_sceduled_log.*,
						 vd.name as venue_name,
						 vd.address as venue_address,
						 mlm.timezone,
						 mf1.feed_back as f_feed_back,
						 mf2.feed_back as t_feed_back,
						 mf1.rating_val as f_rating,
						 mf2.rating_val as t_rating,
						 mf1.no_show as f_no_show,
						 mf1.no_show as f_no_show
						 FROM venue_sceduled_log 
						 INNER JOIN member_master mm1 on mm1.member_id = venue_sceduled_log.from_user 
						 INNER JOIN member_master mm2 on mm2.member_id = venue_sceduled_log.to_user 
						 LEFT JOIN venue_details vd ON (vd.venue_id = venue_sceduled_log.venue_id)
						 LEFT JOIN match_log_master mlm ON (mlm.match_logid = venue_sceduled_log.from_log_id) 
						 LEFT JOIN member_feedbacks mf1 ON (mf1.scheduled_log_id = venue_sceduled_log.log_id 
						 			AND venue_sceduled_log.from_user = mf1.member_id) 
						 LEFT JOIN member_feedbacks mf2 ON (mf2.scheduled_log_id = venue_sceduled_log.log_id 
						 			AND venue_sceduled_log.to_user = mf2.member_id)
						 WHERE DATE(schedule_timefrom)>='$startdate' AND DATE(schedule_timefrom) <='$enddate'
						 GROUP BY venue_sceduled_log.log_id";
					
			//echo $sql; exit;
			$result =  $this->db->query($sql);
			$results =  $result->result_array();
			return $results;
	}
	public function getAvailabilityByDate($startdate,$enddate){
			$sql="SELECT match_log_master.*, IF(vsl.log_id > 0, 
							 IF( vsl.from_user = match_log_master.member_id, vsl.to_user, vsl.from_user),null) as matches,
							 member_master.*,
							 (SELECT SUM(member_points_log.points) FROM member_points_log WHERE member_id = member_master.member_id ) as score , 
							 member_preferences.gender_exclude,
							 member_preferences.exclude_pre_match,
							 GROUP_CONCAT(member_excluded_comapnies.company_id) as companies_exclude,
							 (SELECT IFNULL(COUNT(match_logid),0) 
							 	FROM match_log_venues WHERE match_logid=match_log_master.match_logid  
								GROUP BY match_logid)  as total_venues 		 
						 FROM match_log_master 
						 LEFT JOIN member_master on member_master.member_id = match_log_master.member_id 
						 LEFT JOIN venue_sceduled_log vsl ON (vsl.from_log_id = match_log_master.match_logid OR vsl.to_log_id = match_log_master.match_logid)
						 LEFT JOIN member_preferences on member_master.member_id = member_preferences.member_id 
						 LEFT JOIN member_excluded_comapnies on member_master.member_id = member_excluded_comapnies.member_id 
						 
						 WHERE DATE(match_time_from)>='$startdate' AND DATE(match_time_from) <='$enddate'
						 AND match_log_master.active = 'Y'
						 GROUP BY match_log_master.match_logid
						 ORDER BY match_log_master.created_time ASC";
			//echo $sql; exit;
			$result =  $this->db->query($sql);
			$results =  $result->result_array();
			return $results;
	}
	public function getAvailabilityForApp ($member_id,$date,$prev_availid=0){
		$sql = "SELECT  mlm.*, tml.is_expired as temp_match_status, 
				IF(vsl.log_id > 0, IF( vsl.from_user = $member_id, vsl.to_user, vsl.from_user),null) as matches, 
				IF(vsl.log_id > 0, IF( vsl.from_user = $member_id, vsl.from_log_id, vsl.to_log_id),null) as matchlogid,
				IF(vsl.log_id > 0, IF( vsl.from_user = $member_id, vsl.from_user_status, vsl.to_user_status),null) as review_status, 
				vsl.log_id as schedule_id, COUNT(tml.log_id) as temp_matches
					
				FROM match_log_master mlm
				LEFT JOIN temp_matching_log tml ON ((tml.from_match_logid = mlm.match_logid OR tml.to_match_logid = mlm.match_logid ) AND tml.is_expired = 'N')
				LEFT JOIN venue_sceduled_log vsl ON (vsl.from_log_id = mlm.match_logid OR vsl.to_log_id = mlm.match_logid)
					WHERE mlm.member_id = $member_id 
					AND IF($prev_availid > 0, mlm.match_logid = $prev_availid, DATE_FORMAT(mlm.match_time_from,'%Y-%m-%d') = '$date')";
			
			$result =  $this->db->query($sql);
			$results =  $result->row_array();
			return $results;
	}	
	public function checkAnyPrevAction($member_id){
		if(!$member_id) return false;
		$sql = "SELECT  mlm.match_logid	, DATE_FORMAT( mlm.match_time_from, '%Y-%m-%d') as currentdate		
					FROM match_log_master mlm
					WHERE mlm.member_id = $member_id
					ORDER BY mlm.created_time DESC LIMIT 1 ";
			
			$result =  $this->db->query($sql);
			$results =  $result->row();
			return $results;
		
	}
	public function getAvailabilityDetail($member_id,$match_logid){
	
		$sql="SELECT * from match_log_master WHERE member_id='$member_id' AND match_logid='$match_logid' ";
		$result =  $this->db->query($sql);
		return $result->row_array();
		

	}
	public function getMatchHistoryByMatchlog($startdate,$enddate){
		$todayMatches = $this->getTodaysMatchLogs($startdate,$enddate);
		if($todayMatches){
			$history = array();
			foreach($todayMatches as $todayMatch){
				$history[$todayMatch['match_logid']] = $this->getHistoryMatches($todayMatch['match_logid']);
			}
			return $history;
		}
		return false;
		
		
	}
	public function getHistoryMatches($match_logid){
		
		$sql=" SELECT  member_master.member_id, member_master.picture_url, first_name,last_name, IF( temp_matching_log.from_match_logid = $match_logid, temp_matching_log.to_match_logid, temp_matching_log.from_match_logid)
				        as matchid, temp_matching_log.created_time as matchgot_time
						FROM temp_matching_log
						INNER JOIN match_log_master  ON (IF( temp_matching_log.from_match_logid = $match_logid, temp_matching_log.to_match_logid, temp_matching_log.from_match_logid) = match_log_master.match_logid)
						INNER JOIN member_master ON  member_master.member_id = match_log_master.member_id 
						
						WHERE temp_matching_log.from_match_logid = $match_logid OR  
						temp_matching_log.to_match_logid = $match_logid
						ORDER BY temp_matching_log.created_time DESC ";
			$result =  $this->db->query($sql);
			$results =  $result->result_array();
			return $results;
		
	}
	public function getTodaysMatchLogs($startdate,$enddate){
		
		$sql="SELECT match_log_master.match_logid
						 FROM match_log_master 
						 WHERE DATE(match_time_from)>='$startdate' AND DATE(match_time_from) <='$enddate'
						 AND match_log_master.active = 'Y'";
			$result =  $this->db->query($sql);
			$results =  $result->result_array();
			return $results;
		
	}
	public function getAvailabilityDetailByDate($member_id,$date){
		$sql="SELECT match_log_master.* from match_log_master WHERE DATE(match_time_from)='$date' ";
		$result =  $this->db->query($sql);
		return $result->row_array();
	}
	public function getUserPrevLogID($member_id){
		$sql="SELECT prev_availid from member_master WHERE member_id = '$member_id' ";
		$result =  $this->db->query($sql);
		$out = $result->row_array();
		return $out['prev_availid']?$out['prev_availid']:0;
	}
	public function getAvailabilRestaurant($log_id, $member_id ){
			$sql="SELECT match_log_venues.*, venue_details.name, venue_details.address,
						 venue_details.phone,venue_details.rating,  venue_details.photo_key, 
						 IF(member_favourite_venues.member_id = $member_id, 1,0) as favourite						 
						 FROM match_log_venues 
						 LEFT JOIN member_favourite_venues ON (match_log_venues.venue_id = member_favourite_venues.venue_id )
						 LEFT JOIN venue_details ON (venue_details.venue_id = match_log_venues.venue_id)
						 WHERE match_log_venues.match_logid = $log_id
						 GROUP BY match_log_venues.venue_id";
			$result =  $this->db->query($sql);
			$results =  $result->result_array();	
		return $results;
		
		/*$this->db->select("*");
		$this->db->from("match_log_venues");
		$this->db->where('match_log_venues.match_logid',$member_id);
		$result = $this->db->get();
		return $result->result_array();*/
	}
	
	public function locations($latitude,$longitude)
	{
	
		$dist='4';  
		//$device_time_formated = date("Y-m-d H:i:s",strtotime($visited_date));
		$sql = "SELECT a.*";
			if($latitude != '0.000000')
			{		
				$sql .= ", if((3959*ACOS(COS(RADIANS('".$latitude."'))*COS(RADIANS(a.match_latitude))*COS( RADIANS(a.match_longitude) - RADIANS('".$longitude."')) +SIN( RADIANS('".$latitude."'))*SIN(RADIANS(a.match_latitude )))) != '',(3959*ACOS(COS(RADIANS('".$latitude."'))*COS(RADIANS(a.match_latitude))*COS( RADIANS(a.match_longitude) - RADIANS('".$longitude."')) +SIN( RADIANS('".$latitude."'))*SIN(RADIANS(a.match_latitude )))),0) AS distance ";
			}
		$sql.= ",b.* FROM `match_log_master` a, `member_master` b WHERE a.member_id=b.member_id ";
	//	$sql .= " and visited_date = '$device_time_formated' and b.user_id!=$user_id and a.user_id=b.user_id ";
		
		if($latitude != '0.000000')
		{
			$sql .= "HAVING distance < $dist ";
		}
		//echo $sql;
		
		$query	=	$this->db->query($sql);	
		$result	=	$query->result_array();
		$num=count($result);
		if($num==0)
			return $num;
		else
			return $result;
	
	}
	public function getRecentMatchFromLogid($logid)
	{
		$sql = "SELECT * FROM temp_matching_log 
					WHERE from_match_logid = '$logid' OR to_match_logid = '$logid' 
					AND is_expired = 'N' ORDER BY created_time DESC LIMIT 1";
		$result = $this->db->query($sql);
		$results = $result->row_array();
		return $results;
	}
	

	public function getUserDetails($id)
	{
		$this->db->select('member_master.*,member_profile.member_dob,member_profile.contact_number,member_profile.location,member_profile.country_code');
		$this->db->from('member_master');
		$this->db->where('member_master.member_id', $id); 
		$this->db->join('member_profile', 'member_master.member_id = member_profile.member_id','left');
		$result = $this->db->get();
		$resu=$result->result_object();
		return $resu[0];		
	}
	
	public function getNoShowUser($log_id, $member_id ){
			$sql="SELECT IF(from_user = $member_id, to_user ,from_user) as no_user				 
						 FROM venue_sceduled_log 
						 WHERE log_id = $log_id ";
			$result =  $this->db->query($sql);
			$output =  $result->row_array();	
		return $output['no_user'];
		
	}
	public function insertVenues($prev_venues,$match_logid){
		if(!$match_logid) return false;
		if($prev_venues){
			
			foreach($prev_venues as $prev_venue){
				$arr['venue_id'] 	  = $prev_venue['venue_id'];
				$arr['match_logid']   = $match_logid;
				
				$this->db->insert('match_log_venues', $arr);
				$id[] = $this->db->insert_id();
			}
			
			return $id;
		}
	}
	public function getNoMatchLog($member_id, $date )
	{
		$sql = "SELECT log_id  FROM temp_nomatch_log WHERE  member_id = '$member_id' AND match_date = '$date' AND is_read ='N'
		 ORDER BY created_time DESC LIMIT 1";
		$result = $this->db->query($sql);
		$results = $result->row_array();
		return $results['log_id']?$results['log_id']:0;
	}
	public function update_nomatch_log_read($log_id)
	{
		$array['is_read'] = 'Y';
		$this->db->where('log_id',$log_id);
		$this->db->update('temp_nomatch_log',$array);

		return true;
		
	
	}

public function getAvailability	($match_logid)
	{
	    $sql="SELECT venue_id  from match_log_venues WHERE match_logid='$match_logid'";
		$result =  $this->db->query($sql);
		return $result->result_array();
	}
public function getMatchLocations($match_date,$member_id){
	    $sql="SELECT match_logid, CONCAT(member_master.first_name,' ', member_master.last_name) as name
              FROM match_log_master 
              INNER JOIN member_master ON member_master.member_id = match_log_master.member_id
              WHERE DATE_FORMAT(match_time_from, '%Y-%m-%d') = '$match_date'
              AND match_log_master.member_id != $member_id";
		$result =  $this->db->query($sql);
		return $result->result_array();
}
}