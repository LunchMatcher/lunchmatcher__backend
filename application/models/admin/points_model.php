<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class Points_model extends MY_Model { 
	public $_table = 'member_points_log';
	
	public function setTable($table){
		$this->_table = $table;
	}
	
	public function getAllUsersPoints(){
	
		$sql="SELECT TBL1.id,TBL1.member_id,SUM(TBL1.points) as points,TBL2.* from member_points_log TBL1,member_master TBL2 where TBL1.member_id= TBL2.member_id GROUP BY TBL1.member_id ORDER BY SUM(TBL1.points) DESC";
			
			$result =  $this->db->query($sql);
			$results =  $result->result_array();	
			//echo $str = $this->db->last_query();
			//print_r($results);exit;
			return $results;
			
	}
	public function getUserPointDetails($member_id){
	
		$sql = "select member_points_log.*,DATE_FORMAT(member_points_log.created_date, '%M %d, %Y') as created_date, 
		m1.title, m1.point_type, mm.picture_url
		FROM member_points_log 
		INNER JOIN point_master m1 on m1.point_id = member_points_log.point_id
		LEFT JOIN member_master mm ON (mm.member_id = member_points_log.ref_id)
		where member_points_log.member_id='$member_id' ORDER BY member_points_log.created_date DESC";
		$result =  $this->db->query($sql);
		$results =  $result->result_array();
		return $results;
			
	}
	public function getMemberList($name){
		
			
	
		$name = $name ? strip_tags($name) : '';
		$name = mysql_real_escape_string($name);	
		$qry	=	"select  T1.first_name as first_name,T1.last_name as last_name FROM member_master T1 where 1 ";

		if($name)
			{
				$qry .= " AND  (T1.first_name like '%$name%') OR (T1.last_name like '%$name%')";
			}

		$result = $this->db->query($qry);
		$records = $result->result_array();
		return $records;
				
			
			
		}
		public function getUsersPointsBySearch($name){
	
		$sql="SELECT TBL1.id,TBL1.member_id,SUM(TBL1.points) as points,TBL2.* from member_points_log TBL1,member_master TBL2 where TBL1.member_id= TBL2.member_id ";
		
		if($name)
		{
			$search_array=explode(' ',mysql_real_escape_string($name));
			$sql.=" and (";
			$i=1;
			foreach($search_array as $key)
			{	
				$sql.="TBL2.first_name LIKE '%$key%' or TBL2.last_name LIKE '%$key%' ";
				if($i!=count($search_array))
					$sql.=" or ";
				$i++;
			}
			$sql.=")  ";
		}		
		
			
			
		$sql .= " GROUP BY TBL1.member_id ORDER BY SUM(TBL1.points) DESC";
			$result =  $this->db->query($sql);
			$results =  $result->result_array();	
			//echo $str = $this->db->last_query();
			//print_r($results);exit;
			return $results;
			
	}	
	public function get_all_purchases(){
	
		$sql = "select * from points_forpurchase";
		
					
			$result =  $this->db->query($sql);
			$results =  $result->result_array();	
			//echo $str = $this->db->last_query();
			//print_r($results);exit;
			return $results;
			
	}	
	public function getPurchasesByID($id){
	
		$sql = "select * from points_forpurchase where id=$id";
		
					
			$result =  $this->db->query($sql);
			$results =  $result->row_array();	
			//echo $str = $this->db->last_query();
			//print_r($results);exit;
			return $results;
			
	}
	
}