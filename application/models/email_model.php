<?php

	class email_model extends CI_Model {
	var $table = "general_emails";
		function get_email_template($template_name=""){
					
			if($template_name){
				$query = $this->db->get_where($this->table, array('name'=>$template_name, 'status' => 'Y'));
				return $query->row_array();
			}
					
		}
	}

?>