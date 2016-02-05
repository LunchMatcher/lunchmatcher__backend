<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class User_model extends MY_Model { 
	public $_table = 'member_master';
	
	public function setTable($table){
		$this->_table = $table;
	}

	public function getTodaysAvailabilityCount($date){
	
		$sql = "select COUNT(match_logid) as tot_avail
		FROM match_log_master 
		where DATE_FORMAT(match_time_from, '%Y-%m-%d')='$date' AND active = 'Y'";
		$result =  $this->db->query($sql);
		$results =  $result->row_array();	
		return $results['tot_avail'];
	}
	public function getPointedUsers($date){
	
		$sql = "select COUNT(DISTINCT member_points_log.member_id) as tot_avail
		FROM member_points_log 
		INNER JOIN member_master ON (member_master.member_id =  member_points_log.member_id)
		WHERE member_master.status = 'Y'
		";
		$result =  $this->db->query($sql);
		$results =  $result->row_array();	
		return $results['tot_avail'];
	}
	
	public function getUserCount(){
		$sql = "SELECT COUNT(member_id) as num FROM member_master where status!='T'";
		$result =  $this->db->query($sql);
		$results =  $result->result();	
		$num = $results[0]->num?$results[0]->num:0;
		
		return $num ;
			
	}
	public function getUserLists($status='',$limit, $start=0, $key,$order_by_field='',$order_by_value='ASC'){
		
		$start = $start?$start:0;
		$sql = "SELECT member_master.*, SUM(mpl.points) as tot_score FROM member_master 
				LEFT JOIN member_points_log mpl ON mpl.member_id = member_master.member_id
				WHERE member_master.status='$status' ";
			
		if($status)
			$sql .= " AND member_master.status='$status' ";
		
		if($key != ''){
			$sql .= " AND (member_master.email LIKE '%$key%' OR member_master.first_name LIKE '%$key%' OR member_master.last_name LIKE '%$key%')";	
		}
		
		$sql .= " GROUP BY member_master.member_id ";
		
		if($order_by_field != '')
			$sql .= " ORDER BY $order_by_field $order_by_value";
		else
			$sql .= " ORDER BY created_time DESC";
		
		if($limit)
			$sql .= " LIMIT $start, $limit ";
			
		$result =  $this->db->query($sql);
		$results =  $result->result();
			
		//echo $str = $this->db->last_query();
		
		return $results;
			
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
	public function getUserCompanyDetails($member_id){
		$this->db->select("*");
		$this->db->from("member_current_positions");
		$this->db->where('member_current_positions.member_id',$member_id);
		$result = $this->db->get();
		return $result->result_array();
	}
	public function getUserExcludedCompanies($member_id){
		$this->db->select("*");
		$this->db->from("member_excluded_comapnies");
		$this->db->where('member_id',$member_id);
		$result = $this->db->get();
		return $result->result_array();
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
	public function check_user($id)
	{
		$this->db->select('*');
		$this->db->from('member_profile');
		$this->db->where('member_id', $id); 	
		$result = $this->db->get();
		return $result->num_rows();
	}
	public function updateDtetails($arr,$id)
	{
		$this->db->update('member_profile', $arr,array('member_id' => $id));
		return $this->db->insert_id();	
	}
	public function insertDetails($arr)
	{
		$this->db->insert('member_profile', $arr);
		return $this->db->insert_id();	
	}
	public function getUserData($cond){
		$sql = "select member_master.*,user_types.type,user_details.telephone,user_details.address,user_details.zipcode,
		user_details.lat,user_details.long,user_details.state,user_details.country,
		(select count(*) from delivery_jobs where awarded_to = member_master.id and status = 'intransit') as transit_hauls
		from member_master 
		left join user_types on user_types.id = member_master.type_id
		left join user_details on user_details.user_id = member_master.id ";
		if(is_array($cond))
			$sql .= "where ";
		$i = 0;
		foreach($cond as $key=>$con){
			if($i > 0)
				$sql .= 'and ';
			$sql .= $key." = '".$con."' ";
			$i++;
		}	

		$result = $this->db->query($sql);		
		return $result->row();
	}
 	public function getUserListsByID($member_id){
		$sql = "select * from member_master where member_id='$member_id' ";
		$result =  $this->db->query($sql);
		$results =  $result->row_array();	
		//echo $str = $this->db->last_query();
		return $results;
			
	}
	public function getPreferences($member_id){
		
		$this->db->select("*");
		$this->db->from("member_preferences");
		$this->db->join("member_excluded_comapnies","member_preferences.member_id=member_excluded_comapnies.member_id","left");
		$this->db->where('member_preferences.member_id',$member_id);
		$query = $this->db->get();
		return $query->result_array();
	
			
	}
	public function getFeedbacks($member_id){
		$this->db->select("member_feedbacks.feed_back,member_feedbacks.rating_val,member_feedbacks.created_time,member_master.first_name,member_master.last_name");
		$this->db->from("member_feedbacks");
		$this->db->join("member_master","member_master.member_id=member_feedbacks.given_user");
		$this->db->where('member_feedbacks.member_id',$member_id);
		$result = $this->db->get();
		return $result->result_array();
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
	public function getUserVenueDetails($member_id){
	
		$sql = "select venue_sceduled_log.*,vd.name as name,vd.address as address,vd.photo_key, 
		IF(venue_sceduled_log.from_user=$member_id,m2.first_name,m1.first_name) as matcher_firstname,
		IF(venue_sceduled_log.from_user=$member_id,m2.last_name,m1.last_name) as matcher_lastname,
		IF(venue_sceduled_log.from_user=$member_id,m2.picture_url,m1.picture_url) as matcher_pictureurl
		FROM venue_sceduled_log 
		LEFT JOIN member_master m1 on m1.member_id = venue_sceduled_log.from_user
		LEFT JOIN member_master m2 on m2.member_id = venue_sceduled_log.to_user
		LEFT JOIN venue_details vd on vd.venue_id = venue_sceduled_log.venue_id
		WHERE venue_sceduled_log.from_user=$member_id or venue_sceduled_log.to_user=$member_id";
		
			
			$result =  $this->db->query($sql);
			$results =  $result->result_array();	
			//echo $str = $this->db->last_query();
			//print_r($results);exit;
			return $results;
			
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
	public function insert_fields($table_name,$arr)
	{
		$this->db->insert($table_name, $arr);
		//return $this->db->last_query(); 
		return $this->db->insert_id();
	}
}