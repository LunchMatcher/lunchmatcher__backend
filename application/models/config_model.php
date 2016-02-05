<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class Config_model extends MY_Model { 	
	public $_table = 'general_config';
	
	public function setTable($table){
		$this->_table = $table;
	}
	
	public function get_all_conf_field_n_values(){ 
		$result = $this->get_many_by('display', 'Y');
		return $result;
	}
	public function get_config_value($fieldname){
		$result = $this->get_by(array('field'=>$fieldname));
		return $result->value;
	}
	public function change_password($username,$oldpassword,$newpassword)
	{
		$sql="SELECT * from member_admins where password='$oldpassword' and username='$username'";
		$result =  $this->db->query($sql);
		$results =  $result->row_array();
		if(count($results)!=0)
		{
			$sql2="UPDATE member_admins set password='$newpassword' where username='$username'";
			$result1 =  $this->db->query($sql2);
		}
		return count($results);
	}	
	public function getAllEmailtemplates()
	{
		$sql="SELECT * from general_emails";
		$result =  $this->db->query($sql);
		$results =  $result->result_array();
		return $results;
	}	
	public function getEmilDetails($id)
	{
		$sql="SELECT * from general_emails where email_id=$id";
		$result =  $this->db->query($sql);
		$results =  $result->row_array();
		return $results;
	}	
	public function updateEmailTemplate($email_id,$data)
	{
		$this->db->update('general_emails', $data,array('email_id' => $email_id));
		return $this->db->insert_id();	
	}
		
}