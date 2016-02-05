<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class Feedback_model extends MY_Model { 
	public $_table = 'member_feedbacks';
	
	public function setTable($table){
		$this->_table = $table;
	}
	
	public function checkNoshow($given_user,$log_id,$member_id){
		$sql 	 = "SELECT * FROM member_feedbacks WHERE scheduled_log_id  = $log_id AND given_user = $member_id AND member_id = $given_user";
		
		
		$result  = $this->db->query($sql);
		$row =  $result->row();
		return $row;
	}
	
	public function saveFeedback($arr){
		$scheduled_log_id  = $arr['scheduled_log_id'];
		$member_id		 = $arr['member_id'];
		$given_user		= $arr['given_user'];
		$sql 	 = "SELECT feed_id FROM member_feedbacks 
					where scheduled_log_id = $scheduled_log_id AND member_id =$member_id AND given_user = $given_user ";
		$result  = $this->db->query($sql);
		$nums =  $result->row();
		if($nums->feed_id) {
			$this->db->update('member_feedbacks', $arr,array('feed_id' => $nums->feed_id));
			return $nums->feed_id;
		}
		else{
			$this->db->insert('member_feedbacks', $arr);
			return $this->db->insert_id();
		}
		
	}
	
	public function update_schedule_log_state($log_id,$user_id)
	{
		if(!$log_id || !$user_id) return false;
		
		$sql1 	 = " UPDATE venue_sceduled_log SET from_user_status = 'Y'
		 				WHERE log_id = $log_id AND from_user = $user_id";
		$result  = $this->db->query($sql1);
		
		$sql2 	 = " UPDATE venue_sceduled_log SET to_user_status = 'Y'
		 				WHERE log_id = $log_id AND to_user = $user_id";
		$result  = $this->db->query($sql2);
		
		return $result;
		
	
	}
	
	public function removeVenueFeedBack($venue_id,$given_user){
		$sql 	 = "DELETE FROM venue_feedbacks WHERE given_user = $given_user AND venue_id ='$venue_id' ";
		
		$result  = $this->db->query($sql);
		return $result;
	}
	public function getLogdate($log_id){
		$sql 	 = "SELECT DATE(schedule_timefrom) action_date FROM venue_sceduled_log WHERE log_id = $log_id";
		$result  = $this->db->query($sql);
		$results =  $result->row();
		return $results->action_date;
	}
	
	public function removePointsFromDBForNoShow($point_id,$member_id,$log_id){
		$sql 	 = "DELETE FROM member_points_log WHERE point_id = $point_id AND member_id = $member_id AND ref_id = $log_id ";
		
		$result  = $this->db->query($sql);
		return $result;
	}
	
	public function saveVenueFeedback($arr){
		
		//echo "<pre>"; print_r($arr);  echo "</pre>"; exit;
		$scheduled_log_id  = $arr['scheduled_log_id'];
		$venue_id		 = $arr['venue_id'];
		$given_user		= $arr['given_user'];
		$sql 	 = "SELECT feed_id FROM venue_feedbacks 
					where scheduled_log_id = $scheduled_log_id AND venue_id ='$venue_id' AND given_user = $given_user ";
		$result  = $this->db->query($sql);
		$nums =  $result->row();
		if($nums->feed_id) {
			$this->db->update('venue_feedbacks', $arr,array('feed_id' => $nums->feed_id));
			return true;
		}
		else{
			$this->db->insert('venue_feedbacks', $arr);
			return $this->db->insert_id();
		}
		
	}
	
	
	
	public function getNoOfFeedbacks($status,$member_id){
		$sql 	 = "SELECT COUNT(feed_id) from member_feedbacks where member_id = $member_id AND avg ='$status' AND checked='N'";
		$result  = $this->db->query($sql);
		$results =  $result->row();
		return $results;
	}
	
	
	public function insertPointsToDB($status,$member_id){
		$sql 	 = "UPDATE member_feedbacks SET checked = 'N' where member_id = $member_id AND avg ='$status' AND checked='N'";
		$result  = $this->db->query($sql);
		return $result;
	}
	
}