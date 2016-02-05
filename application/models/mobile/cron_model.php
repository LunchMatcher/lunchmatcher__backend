<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class Cron_model extends CI_Model { 

	public function  __construct()
	{
	$this->load->database();
	}
	public function getNoMatchLog($member_id, $match_logid )
	{
		$sql = "SELECT log_id  FROM temp_nomatch_log WHERE match_logid = '$match_logid' AND member_id = '$member_id' AND is_read ='N'
		 ORDER BY created_time DESC LIMIT 1";
		$result = $this->db->query($sql);
		$results = $result->row_array();
		return $results['log_id']?$results['log_id']:0;
	}
	
	public function update_temp_log($data, $log_id){
		$result = $this->db->update('temp_matching_log', $data,array('log_id' => $log_id));
		return $result;
	}
	public function updateVenueMeetingCount($venue_id)
	{
		if(!$venue_id) return false;
		$sql = "UPDATE venue_details SET meeting_count = meeting_count+1 
					WHERE venue_id = '$venue_id'";
		$result = $this->db->query($sql);
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
	
	public function update_nomatch_log_read($log_id)
	{
		$array['is_read'] = 'Y';
		$this->db->where('log_id',$log_id);
		$this->db->update('temp_nomatch_log',$array);

		return true;
	}
	
	
	
	
	public function getUser($cond){
		$this->db->select("*");
		$this->db->from("member_master");
		$this->db->where($cond);
		$query = $this->db->get();
		return $query->row();
	}
	public function getAllLogUsers($date,$crone_time){
	
				
			/*$sql="SELECT 
				m.match_logid,
				m.timezone,
				m.member_id,
				m.match_time_from,
				m.match_time_to,
				DATE_SUB(m.match_time_to,INTERVAL 2 HOUR) as last_notific,
				me.gender,
				CONCAT(DATE_FORMAT(m.match_time_from,'%T'),' - ',DATE_FORMAT(m.match_time_to,'%T')) as avail_time,
				m.match_latitude,
				m.match_longitude,
				m.match_radius,
				m.created_time,
				mp.gender_exclude,
				mp.exclude_pre_match,
				v.match_logid as venue_match_logid,
				GROUP_CONCAT(DISTINCT v.venue_id) as venues,
				GROUP_CONCAT(DISTINCT c.company_id) as companies,
				GROUP_CONCAT(DISTINCT mc.company_id) as excluded_companies,
				(SELECT sum(points) FROM member_points_log where member_id= m.member_id)  as points				
				FROM match_log_master m 
				INNER JOIN member_master me
					ON m.member_id=me.member_id
				LEFT JOIN match_log_venues v
					ON m.match_logid=v.match_logid 
				LEFT JOIN member_current_positions c
					ON m.member_id=c.member_id
				LEFT JOIN member_preferences mp
					ON m.member_id=mp.member_id
				LEFT JOIN member_excluded_comapnies mc
					ON m.member_id=mc.member_id
				WHERE m.active = 'Y'
				AND( DATE_SUB(m.match_time_from,INTERVAL 2 HOUR) <= '$crone_time' AND DATE_SUB(m.match_time_to,INTERVAL 2 HOUR) > '$crone_time') 
				AND m.match_logid NOT IN 
				( select from_log_id as match_logid FROM venue_sceduled_log 
					WHERE DATE_FORMAT( schedule_timefrom, '%Y-%m-%d' ) ='$date' 
					UNION select to_log_id as match_logid FROM venue_sceduled_log 
					WHERE DATE_FORMAT( schedule_timefrom, '%Y-%m-%d' ) ='$date' )
				
				GROUP BY m.member_id ORDER BY points DESC, m.created_time ASC";*/
				
			$min_point = $this->config->item('availability_min_point')?$this->config->item('availability_min_point'):5;
			
			
		    $sql="
				SELECT temp.* FROM (			
					SELECT 
					m.match_logid,
					m.timezone,
					m.member_id,
					m.match_time_from,
					m.match_time_to,
					DATE_SUB(m.match_time_to,INTERVAL 2 HOUR) as last_notific,
					me.gender,
					CONCAT(DATE_FORMAT(m.match_time_from,'%T'),' - ',DATE_FORMAT(m.match_time_to,'%T')) as avail_time,
					m.match_latitude,
					m.match_longitude,
					m.match_radius,
					m.created_time,
					mp.gender_exclude,
					mp.exclude_pre_match,
					v.match_logid as venue_match_logid,
					GROUP_CONCAT(DISTINCT v.venue_id) as venues,
					GROUP_CONCAT(DISTINCT c.company_id) as companies,
					GROUP_CONCAT(DISTINCT mc.company_id) as excluded_companies,
					(SELECT sum(points) FROM member_points_log where member_id= m.member_id)  as points				
					FROM match_log_master m 
					INNER JOIN member_master me
						ON m.member_id=me.member_id
					LEFT JOIN match_log_venues v
						ON m.match_logid=v.match_logid 
					LEFT JOIN member_current_positions c
						ON m.member_id=c.member_id
					LEFT JOIN member_preferences mp
						ON m.member_id=mp.member_id
					LEFT JOIN member_excluded_comapnies mc
						ON m.member_id=mc.member_id
					WHERE m.active = 'Y'
					AND( m.notification_start <= '$crone_time' AND DATE_SUB(m.match_time_to,INTERVAL 2 HOUR) > '$crone_time') 
					AND m.match_logid NOT IN 
					( select from_log_id as match_logid FROM venue_sceduled_log 
						WHERE DATE_FORMAT( schedule_timefrom, '%Y-%m-%d' ) ='$date' 
						UNION select to_log_id as match_logid FROM venue_sceduled_log 
						WHERE DATE_FORMAT( schedule_timefrom, '%Y-%m-%d' ) ='$date' )
					GROUP BY m.member_id
				) temp
				WHERE temp.points > $min_point
				 ORDER BY temp.points DESC, temp.created_time ASC";
				
			//points DESC
			//echo $sql; exit;
			$result =  $this->db->query($sql);
			$results =  $result->result_array();	
			return $results;
			
	}
	public function getMyUsers($user_id,$from_time,$to_time)
	{
		$sql = "SELECT 
				m.match_logid,
				m.member_id,
				m.match_time_from,
				m.match_time_to,
				CONCAT(DATE_FORMAT(m.match_time_from,'%T'),' - ',DATE_FORMAT(m.match_time_to,'%T')) as avail_time,
				m.match_latitude,
				m.match_longitude,
				m.match_radius,
				m.created_time,
				mp.gender_exclude,
				mp.exclude_pre_match,
				v.match_logid as venue_match_logid,
				GROUP_CONCAT(v.venue_id) as venues
				FROM match_log_master m 	
				INNER JOIN member_master me
					ON m.member_id=me.member_id
				LEFT JOIN match_log_venues v
					ON m.match_logid=v.match_logid 
				LEFT JOIN member_current_positions c
					ON m.member_id=c.member_id
				LEFT JOIN member_points_log p
					ON m.member_id=p.member_id
				LEFT JOIN member_preferences mp
					ON m.member_id=mp.member_id
				LEFT JOIN member_excluded_comapnies mc
					ON m.member_id=mc.member_id
				WHERE (m.match_time_from = '$from_time' && m.match_time_to = '$to_time') and m.member_id!=$user_id
				GROUP BY member_id ORDER BY match_time_from ASC,match_time_to ASC";
				
				//WHERE (( m.match_time_from BETWEEN '$from_time' and '$to_time' ) OR ( m.match_time_to BETWEEN '$from_time' and '$to_time' ) OR (m.match_time_from <= '$from_time' && m.match_time_to >= '$to_time')) 
				//AND m.member_id NOT IN ($not_in_list)
		
		$result = $this->db->query($sql);
		$results = $result->result_array();
		return $results;
	
	}
	public function getExpiredNotificUser($date,$from_time,$to_time){
		
		$sql=" SELECT tmp_log.log_id, log_master.member_id FROM (
					SELECT log_id, from_match_logid as temp_log_id FROM temp_matching_log WHERE point_deducted = 'N'
					AND from_match_status=''
					UNION 
					SELECT log_id, to_match_logid as temp_log_id FROM temp_matching_log WHERE point_deducted = 'N'
					AND to_match_status=''
				)tmp_log
				INNER JOIN (
							SELECT m.match_logid, m.member_id
								FROM match_log_master m
								WHERE (
								m.match_time_from > '$from_time'
								AND m.match_time_from <= '$to_time'
								)
								AND m.match_logid NOT
								IN (
								
								SELECT from_log_id AS match_logid
								FROM venue_sceduled_log
								WHERE DATE_FORMAT( schedule_timefrom, '%Y-%m-%d' ) = '$date'
								UNION SELECT to_log_id AS match_logid
								FROM venue_sceduled_log
								WHERE DATE_FORMAT( schedule_timefrom, '%Y-%m-%d' ) = '$date'
								)
								
								GROUP BY member_id
				) log_master ON (tmp_log.temp_log_id = log_master.match_logid)	";
				
				
		$result = $this->db->query($sql);
		$results = $result->result_array();
		return $results;
		
	}
	public function checkTempLog($logid_match){
		$sql="	SELECT COUNT(log_id) as notific
				 FROM temp_matching_log 
				 where to_match_logid = $logid_match 
				 	OR  from_match_logid = $logid_match
				";
				  
		$result =  $this->db->query($sql);
		$output =  $result->row();	
		return $output->notific?$output->notific:0;
		
	}
	public function getMatchOwnerid($log_id ,$member_id)
	{
		$sql="	SELECT match_log_master.member_id as owner_id
				 FROM match_log_master 
				 INNER JOIN temp_matching_log ON (temp_matching_log.to_match_logid = match_log_master.match_logid 
				 									OR  temp_matching_log.from_match_logid = match_log_master.match_logid )
				 where temp_matching_log.log_id = $log_id
				 AND match_log_master.member_id != $member_id
				";
				  
		$result =  $this->db->query($sql);
		$output =  $result->row_array();	
		return $output['owner_id'];
	}
	public function insertTempmatch($data)
	{
		$result = $this->db->insert('temp_matching_log', $data); 
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	public function checkLog($id,$match_id)
	{	$date=date('Y-m-d');
		$this->db->where(array('from_match_logid'=>$id,'to_match_logid'=>$match_id,'match_date'=>$date));
		$query=$this->db->get('temp_matching_log');
		//$this->db->last_query();
		if($query->num_rows()!=0)
			return 1;
		else
			return 0;
	}
	
	public function getTodaysMatchUsers($date){
	
		$sql="SELECT from_match_logid,to_match_logid FROM temp_matching_log where DATE_FORMAT(match_date,'%Y-%m-%d')='$date'";
		$result =  $this->db->query($sql);
		$results =  $result->result_array();	
				
		$outputArray=array();
		foreach($results  as $row){
			$outputArray[$row['from_match_logid']][]=$row['to_match_logid'];
		}
		
		foreach($results  as $row){
			$outputArray[$row['to_match_logid']][]=$row['from_match_logid'];
		}		
		
		return $outputArray;
			
	}
	public function getEndingUserList($date){
		
		$sql = "SELECT * FROM (`venue_sceduled_log`) WHERE `schedule_timeto` <= '$date' 
					AND (from_user_status IS NULL OR from_user_status = '') AND (to_user_status IS NULL OR to_user_status = '')";
		
		$result = $this->db->query($sql);
		$results = $result->result_array();
		//echo $this->db->last_query(); exit;
		return $results;
	
	}
	public function checkPreMatch($member_id,$member_id_match){
	
		$sql = "SELECT log_id from venue_sceduled_log WHERE from_user_status != '' AND to_user_status != '' AND ((from_user=$member_id and to_user=$member_id_match) OR (from_user=$member_id_match and to_user=$member_id))";
		
		$result = $this->db->query($sql);
		$results = $result->result_array();
	
		//print_r($results);
		return $results?count($results):0;
	}
	
	public function insertNoMatch($arr)
	{
		$member_id = $arr['member_id'];
		$this->db->where('member_id',$member_id);
		$this->db->update('temp_nomatch_log',array('is_read'=>'Y'));
	
		$this->db->insert('temp_nomatch_log',$arr);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	
	}
	public function insert_cron_log($name='')
	{
	
		$date	= date('Y-m-d H:i:s');
		$this->db->insert('cron_log',array('cron_name'=>$name,'cron_time'=>$date));
		$insert_id = $this->db->insert_id();
		return $insert_id;
	
	}
	public function update_cron_log($insert_array)
	{
		if($insert_array['cron_id']!=''){
		
		$this->db->where('cron_id',$insert_array['cron_id']);
		$this->db->update('cron_log',$insert_array);

		}	
	}
	public function updateTempLogExpire($from_time){
		$array['is_expired'] = 'Y';
		$this->db->where('created_time < ',$from_time);
		$this->db->update('temp_matching_log',$array);
		return true;
	}
	
	public function updateMatchSceduleExpire($log_id,$who=''){
		if($who == 'to_log_id')
			$array['to_user_status'] = 'N';
		elseif($who == 'from_log_id')
			$array['from_user_status'] = 'N';
		else
			return false;
			
		$this->db->where('log_id ',$log_id);
		$this->db->update('venue_sceduled_log',$array);
		return true;
	}
	
	
	
	
	public function getDailyReminderList($time,$date){
		
		$sql = "SELECT mm.* FROM member_preferences mpr 
						INNER JOIN member_master mm ON (mm.member_id = mpr.member_id)
						WHERE notification_time = '$time' 
					    AND mm.`status` = 'Y'
						AND mm.member_id NOT IN ( SELECT member_id FROM match_log_master WHERE DATE_FORMAT( match_time_from, '%Y-%m-%d' ) = '$date' ) ";
		
		
		
		$result = $this->db->query($sql);
		$results = $result->result();
	//	echo $this->db->last_query(); exit;
		return $results;
	
	}
	public function deleteYesterdayLog($date){
		$sql = "DELETE FROM cron_log WHERE DATE_FORMAT( cron_time, '%Y-%m-%d' ) < '$date' ";
		$result = $this->db->query($sql);
		return true;
	}
	
}