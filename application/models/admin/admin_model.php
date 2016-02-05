<?php 
class Admin_model extends MY_Model {
	
	public $_table = 'member_admins';
	
	public function __construct()
	{
		$this->_database = $this->db;
	}
	public function setTable($table){
		$this->_table = $table;
	}
	
	public function getUser(){
		$this->db->select("*");
		$this->db->from("member_admins");
		$query = $this->db->get();
		return $query->row();
	}
	
	
	public function getadmindetail($username){
		$sql="select * from member_admins where username='$username'";
		$query =  $this->db->query($sql);
		$result = $query->result();	
		return $result[0];
	}
	
}