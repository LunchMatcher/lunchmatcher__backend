<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class Venue_model extends MY_Model { 
	public $_table = 'venue_sceduled_log';
	
	public function setTable($table){
		$this->_table = $table;
	}
	
	public function getAllScheduledVenues($startdate,$enddate){
	
		//$sql="SELECT TBL1.venue_id,count(TBL1.venue_id)as count,TBL2.* from venue_sceduled_log TBL1,venue_details TBL2 where TBL1.completed='Y' and TBL1.venue_id=TBL2.venue_id GROUP BY TBL1.venue_id ORDER BY count(TBL1.venue_id) DESC";
		$sql="SELECT TBL1.venue_id,count(TBL1.venue_id)as count,TBL1.schedule_timefrom,TBL1.schedule_timeto,TBL2.* from venue_sceduled_log TBL1,venue_details TBL2 where  TBL1.venue_id=TBL2.venue_id and DATE(TBL1.schedule_timefrom) >='$startdate' and  DATE(TBL1.schedule_timefrom) <='$enddate' GROUP BY TBL1.venue_id ORDER BY count(TBL1.venue_id) DESC";	
			$result =  $this->db->query($sql);
			$results =  $result->result_array();	
			//echo $str = $this->db->last_query();
			//print_r($results);exit;
			return $results;
			
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
	
	
		
	
	public function getVenueUserDetails($venue_id){
	
		$sql = "select venue_sceduled_log.*,
		m1.first_name as user1_firstname,m1.last_name as user1_last_name,
		m2.first_name as user2_firstname,m2.last_name as user2_last_name,
		m1.picture_url as user1_picture_url,m2.picture_url as user2_picture_url
		from venue_sceduled_log 
		left join member_master m1 on m1.member_id = venue_sceduled_log.from_user
		left join member_master m2 on m2.member_id = venue_sceduled_log.to_user
		where venue_sceduled_log.venue_id='$venue_id' ";
		
					
			$result =  $this->db->query($sql);
			$results =  $result->result_array();	
			//echo $str = $this->db->last_query();
			//print_r($results);exit;
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
	function getMatchVenueDetails($log_id){
		$sql="select * from match_log_venues where match_logid = $log_id";
		$result =  $this->db->query($sql);
		$results =  $result->result_array();
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
	public function delete_existing_venues($match_logid)
	{
		$sql = "DELETE FROM match_log_venues where match_logid = $match_logid";
		$result = $this->db->query($sql);
		return $result;
	}
	public function insert_unique($arr)
	{
		$insert_query = $this->db->insert_string('venue_details', $arr);
		$insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
		$result = $this->db->query($insert_query);
		return $result;
	}
	public function getVenueFromMatchLogid($logId)
	{
		$sql = "SELECT venue_id FROM venue_sceduled_log  WHERE from_log_id = $logId OR to_log_id = $logId";
		$result = $this->db->query($sql);
		$results =  $result->result_array();
		return $results[0];
	}
	
	
	   public function getVenuLists($limit, $start=0, $key){
		//echo $status.'==='.$limit.'==='. $start; exit;
		$start = $start?$start:0;
		$sql = "SELECT * from venue_details WHERE 1 ";
		
		
		/*$sql = "SELECT member_master.*, SUM(mpl.points) as tot_score FROM member_master 
				LEFT JOIN member_points_log mpl ON mpl.member_id = member_master.member_id
				WHERE member_master.status!='T' ";*/    //old code
	
		///echo $key;
		if($key != ''){
$sql .= " AND (venue_details.name LIKE '%$key%' OR venue_details.address LIKE '%$key%'
 OR venue_details.latitude LIKE '%$key%')";	
		}

		
		
		
		if($limit)
			$sql .= " LIMIT $start, $limit ";
			
		$result =  $this->db->query($sql);
		//echo	 $this->db->last_query(); exit;
		$results =  $result->result();
			
		//echo $str = $this->db->last_query(); exit;
	
		return $results;
			
	}
	 public function getVenuCount($key=''){
		$sql = "SELECT COUNT(id) as num FROM venue_details where 1 ";
		if($key != ''){
			$sql .= " AND (venue_details.name LIKE '%$key%' OR venue_details.address LIKE '%$key%'
			 OR venue_details.latitude LIKE '%$key%')";	
		}
		$result =  $this->db->query($sql);
		$results =  $result->result();	
		$num = $results[0]->num?$results[0]->num:0;
		
		return $num ;
			
	}
	
	public function getVenueList()
{
	$query=$this->db->get('venue_details');
	return $query->result_array();
}	
	
	
	
	public function getVenueListDetails($id)
	{

			$sql = "SELECT * from venue_details WHERE id=$id";
		
			$result =  $this->db->query($sql);
			$results =  $result->row_array();	
			//echo $sql; exit;
			return $results;
	
	}
	
	
	public function getFeedbacklist($id)

    {

			$sql ="SELECT vf.rating_val, vf.feed_back, vf.created_time, CONCAT(mm.first_name,' ',mm.last_name) as name
FROM venue_feedbacks vf
LEFT JOIN member_master mm on mm.member_id= vf.given_user
WHERE vf.venue_id ='$id'";
					
			//echo $sql; exit;
			 
			$result =  $this->db->query($sql);
			$results =  $result->result_array();	
			return $results;
	 
	}	
	
	
	public function upDAteImage($dataup,$id)
     {
		 $sql = "SELECT image from venue_details WHERE id=$id";
		 $resu =  $this->db->query($sql);
		 $img =  $resu->row_array();
		 $old_image = $img['image']; 
		 $oldimage=FCPATH.'uploads/venue/'.$old_image;
		 if($old_image != '' && file_exists($oldimage)){
			 unlink($oldimage);
		 }
		     
		 $this->db->where('id',$id);
		 $this->db->update('venue_details',$dataup);
		return true;	
	}

	
	
	
	
}