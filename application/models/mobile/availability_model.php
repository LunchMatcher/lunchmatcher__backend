<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class Availability_model extends MY_Model { 
	public $_table = 'match_log_master';
	
	public function setTable($table){
		$this->_table = $table;
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
	
	public function getRecentMatchFromLogid($logid)
	{
		$sql = "SELECT * FROM temp_matching_log 
					WHERE from_match_logid = '$logid' OR to_match_logid = '$logid' 
					AND is_expired = 'N' ORDER BY created_time DESC LIMIT 1";
		$result = $this->db->query($sql);
		$results = $result->row_array();
		return $results;
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
	
	public function updateAvailabilityDetail($id, $data){
		$this->db->where('match_logid', $id);
		$result =  $this->db->update('match_log_master', $data); 
		return $result;
	}
	
	public function getMatchVenues($match_logid){
		$this->db->select("*");
		$this->db->from("match_log_venues");
		$this->db->where('match_logid', $match_logid); 
		$result = $this->db->get();
		return $result->result_array();
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
	
	public function getUserPrevLogID($member_id){
		$sql="SELECT prev_availid from member_master WHERE member_id = '$member_id' ";
		$result =  $this->db->query($sql);
		$out = $result->row_array();
		return $out['prev_availid']?$out['prev_availid']:0;
	}
	
	public function getAvailabilityDetail($member_id,$match_logid){
	
		$sql="SELECT * from match_log_master WHERE member_id='$member_id' AND match_logid='$match_logid' ";
		$result =  $this->db->query($sql);
		return $result->row_array();
		

	}
	public function getNoShowUser($log_id, $member_id ){
			$sql="SELECT IF(from_user = $member_id, to_user ,from_user) as no_user				 
						 FROM venue_sceduled_log 
						 WHERE log_id = $log_id ";
			$result =  $this->db->query($sql);
			$output =  $result->row_array();	
		return $output['no_user'];
		
	}
	
	public function updateMemberPrevAvailability($member_id, $avlid){
		if(!$member_id) return false;
		$sql = "UPDATE member_master set prev_availid = $avlid where member_id = $member_id";
		$result =  $this->db->query($sql);
		return $result;
	}
	public function changeAvailabilityStatus( $avlid){
		if(!$avlid) return false;
		$sql = "UPDATE match_log_master set active = 'Y' where match_logid = $avlid";
		$result =  $this->db->query($sql);
		return $result;
	}
	
	
	public function get_all_availability(){
		$this->db->select("*");
		$this->db->from("match_log_master");
		$this->db->join("member_master","member_master.member_id=match_log_master.member_id");
		$result = $this->db->get();
		return $result->result_array();
	}
	
	
	
	
	public function getAvailabilityDetailByDate($member_id,$date){
		$sql="SELECT match_log_master.* from match_log_master WHERE DATE(match_time_from)='$date' ";
		$result =  $this->db->query($sql);
		return $result->row_array();
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
	

}