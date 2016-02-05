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
	public function getVenueScheduledDetails($venue_id,$schedule_timefrom,$schedule_timeto){
	
    		$sql = "SELECT vsl.*, CONCAT(m1.first_name,' ', m1.last_name) as from_user_name
			, CONCAT(m2.first_name,' ', m2.last_name) as to_user_name
			, m1.picture_url as from_user_picture_url,m2.picture_url as to_user_picture_url
			,(SELECT company_name FROM member_current_positions WHERE member_id =  vsl.from_user LIMIT 1 ) as from_user_company
			,(SELECT position_title FROM member_current_positions WHERE member_id =  vsl.from_user LIMIT 1 ) as from_user_position	
			,(SELECT company_name FROM member_current_positions WHERE member_id =  vsl.to_user LIMIT 1 ) as to_user_company
			,(SELECT position_title FROM member_current_positions WHERE member_id =  vsl.to_user LIMIT 1 ) as to_user_position	
			FROM venue_sceduled_log vsl
			LEFT JOIN member_master m1 ON(m1.member_id = vsl.from_user)
			LEFT JOIN member_master m2 ON(m2.member_id = vsl.to_user)
			WHERE vsl.venue_id = '$venue_id'
			AND DATE_FORMAT(vsl.schedule_timefrom, '%Y-%m-%d') >= '$schedule_timefrom' 
			AND DATE_FORMAT(vsl.schedule_timefrom, '%Y-%m-%d') <= '$schedule_timeto'
			ORDER BY vsl.created_time DESC";

			$result =  $this->db->query($sql);
			$results =  $result->result_array();	
			
			return $results;
	}


	public function getVenuCount($key='',$status=''){
		$sql = "SELECT COUNT(id) as num FROM venue_details where 1 ";
		if($key != ''){
			$sql .= " AND (venue_details.name LIKE '%$key%' OR venue_details.address LIKE '%$key%'
			 OR venue_details.latitude LIKE '%$key%') ";	
		}
		if($status!='')
			$sql .=" AND is_block='$status' ";
		$result =  $this->db->query($sql);
		$results =  $result->result();	
		$num = $results[0]->num?$results[0]->num:0;
		
		return $num ;
			
	}
	public function getVenuLists($limit, $start=0, $key,$status){
		//echo $status.'==='.$limit.'==='. $start; exit;
		$start = $start?$start:0;
		$sql = "SELECT * from venue_details WHERE 1 ";
		
		if($key != ''){
			$sql .= " AND (venue_details.name LIKE '%$key%' OR venue_details.address LIKE '%$key%'
			 OR venue_details.latitude LIKE '%$key%')";	
		}

		if($status!='')
			$sql .=" AND is_block='$status' ";
		
		if($limit)
			$sql .= " LIMIT $start, $limit ";
			
		$result =  $this->db->query($sql);
		// $this->db->last_query();
		// exit;
		$results =  $result->result();
			
		//echo $str = $this->db->last_query(); exit;
	
		return $results;
			
	}
	public function getVenueListDetails($id)
	{

			$sql = "SELECT * from venue_details WHERE id=$id";
		
			$result =  $this->db->query($sql);
			$results =  $result->row_array();	
			//echo $sql; exit;
			return $results;
	
	}
	public function upDAteImage($dataup,$id)
     {
		 $sql = "SELECT image from venue_details WHERE venue_id='$id'";
		 $resu =  $this->db->query($sql);
		 $img =  $resu->row_array();
		 $old_image = $img['image']; 
		 $oldimage=FCPATH.'uploads/venue/'.$old_image;
		 //if($old_image != '' && file_exists($oldimage)){
		//	 unlink($oldimage);
		 //}
		     
		 $this->db->where('venue_id',$id);
		 $this->db->update('venue_details',$dataup);
		return true;	
	}
	public function getFeedbacklist($id)

    {

			$sql =" SELECT vf.rating_val, vf.feed_back, vf.created_time, 
					CONCAT(mm.first_name,' ',mm.last_name) as name
					, mm.picture_url
					FROM venue_feedbacks vf
					LEFT JOIN member_master mm on mm.member_id= vf.given_user
					WHERE vf.venue_id ='$id'";
					
			//echo $sql; exit;
			 
			$result =  $this->db->query($sql);
			$results =  $result->result_array();	
			return $results;
	 
	}	
	public function getFeedbackMeeting($id,$f_member_id,$t_member_id)

    {

			$sql =" SELECT vf.rating_val, vf.feed_back, vf.created_time, 
					CONCAT(mm.first_name,' ',mm.last_name) as name
					, mm.picture_url
					FROM venue_feedbacks vf
					LEFT JOIN member_master mm on mm.member_id= vf.given_user
					WHERE vf.venue_id ='$id' AND (vf.given_user = $f_member_id OR vf.given_user = $t_member_id)
					";
					
			//echo $sql; exit;
			 
			$result =  $this->db->query($sql);
			$results =  $result->result_array();	
			return $results;
	 
	}	
}