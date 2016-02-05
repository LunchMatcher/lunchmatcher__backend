<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class Venue_model extends MY_Model { 
	public $_table = 'venue_sceduled_log';
	
	public function setTable($table){ 
		$this->_table = $table;
	}
	
	public function getVenuDet($venue_id)
	{

			$sql = "SELECT * from venue_details WHERE venue_id= '$venue_id' ";
		
			$result =  $this->db->query($sql);
			$results =  $result->row();	
			//echo $sql; exit;
			return $results;
	
	}
	function getMatchVenueDetails($log_id){
		$sql="select * from match_log_venues where match_logid = $log_id";
		$result =  $this->db->query($sql);
		$results =  $result->result_array();
		return $results;
	}
	
	function getMatchScheduleVenues($from_log_id,$to_log_id){
		
			
			$sql="
				SELECT venue_details.venue_id FROM
			((SELECT * FROM match_log_venues WHERE match_logid = $from_log_id) AS table1
			  INNER JOIN
			 (SELECT * FROM match_log_venues WHERE match_logid = $to_log_id) AS table2
				ON table1.venue_id = table2.venue_id
			) 
			LEFT JOIN venue_details
				ON venue_details.venue_id = table1.venue_id
			ORDER BY venue_details.meeting_count ASC, venue_details.id  ASC
			";
			
			$result =  $this->db->query($sql);
			$results =  $result->result_array();
			return $results[0];
	}
	
	public function delete_existing_venues($match_logid)
	{
		if(!$match_logid) return false;
		$sql = "DELETE FROM match_log_venues where match_logid = $match_logid";
		$result = $this->db->query($sql);
		return $result;
	}
	
	public function insert_unique($arr)
	{
		/*$insert_query = $this->db->insert_string('venue_details', $arr);
		$insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
		$result = $this->db->query($insert_query);*/
		
		
		$sql = "SELECT venue_id FROM venue_details  WHERE venue_id = '{$arr[venue_id]}'";
		$result = $this->db->query($sql);
		$row =  $result->row();
		if($row){
			$venue_id  = $arr['venue_id'];
			unset($arr['venue_id']);
			$this->db->update('venue_details', $arr,array('venue_id' => $venue_id));
			return $venue_id;
		}
		else{	
		
			$this->db->insert('venue_details', $arr);
			return $this->db->insert_id();
		}
	}
	
	
	
	public function getCountOfScheduledVenues($startdate,$enddate){
	
		//$sql="SELECT TBL1.venue_id,count(TBL1.venue_id)as count,TBL2.* from venue_sceduled_log TBL1,venue_details TBL2 where TBL1.completed='Y' and TBL1.venue_id=TBL2.venue_id GROUP BY TBL1.venue_id ORDER BY count(TBL1.venue_id) DESC";
		$sql="SELECT TBL1.venue_id,count(TBL1.venue_id)as count from venue_sceduled_log TBL1 where  DATE(TBL1.schedule_timefrom) >='$startdate' and  DATE(TBL1.schedule_timefrom) <='$enddate' GROUP BY TBL1.venue_id ORDER BY count(TBL1.venue_id) DESC";	
			$result =  $this->db->query($sql);
			$results =  $result->result_array();	
			echo $str = $this->db->last_query();
			//print_r($results);exit;
			return $results;
			
	}
	
	
	function updateVenueSchedLog($data){
		$log_id 			= $data['log_id'];
		unset($data['log_id']);
	/*	$arr1 				= array_flip($this->getFieldNames('venue_sceduled_log'));  		
		$arr 				= array_intersect_key($data, $arr1);*/
		$result 			= $this->db->update('venue_sceduled_log', $data,array('log_id' => $log_id));
		return $result;
	}
	
	function getFavsFlat($member_id)
	{
		$sql = "SELECT venue_id FROM member_favourite_venues  WHERE member_id = $member_id";
		$result = $this->db->query($sql);
		$results =  $result->result_array();
		$b = array();
			foreach($results as $a) {
			   $b[] = $a['venue_id'];
			}
		return $b;
	}
	
	public function getVenueFromMatchLogid($logId)
	{
		$sql = "SELECT venue_id FROM venue_sceduled_log  WHERE from_log_id = $logId OR to_log_id = $logId";
		$result = $this->db->query($sql);
		$results =  $result->result_array();
		return $results[0];
	}

	public function getVenueList()
	{
		$query=$this->db->get('venue_details');
		return $query->result_array();
	}		
	
}