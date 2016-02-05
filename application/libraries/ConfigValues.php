<?php
class ConfigValues{
	public function __construct()
    {
		$CI =& get_instance();
        $query = $CI->db->get('general_config');
		$configs	=	$query->result();
		foreach($configs as $config){
			if($config->field!="id")
				$CI->config->set_item($config->field, $config->value);
			}
			
    }
 	
}
?>