<?php
/*
 * This is a PHP library that handles calling Custom.
 *    - Documentation and latest version
 *          http://recaptcha.net/plugins/php/
 *    - Get a reCAPTCHA API Key
 *          https://www.google.com/recaptcha/admin/create
 *    - Discussion group
 *          http://groups.google.com/group/recaptcha
 *
 * Copyright (c) 2007 reCAPTCHA -- http://recaptcha.net
 * AUTHORS:
 *   Asker
 *   
 *
 */

/**
 * The reCAPTCHA server URL's
 */

/**
 * Encodes the given data into a query string format
 * @param $data - array of string elements to be encoded
 * @return string - encoded request
 */
 
 /* Print the output value */

	if (!function_exists('_number_format')) {
	function _number_format ($number, $echo=false) {       
		
			if($echo)
				return number_format($number, 2, '.', ',');
			else
				echo number_format($number, 2, '.', ',');
			
		
	}

	}
	if (!function_exists('_viewdate')) {
		function _viewdate ($string,$echo=false) {       
			$CI =& get_instance();
			$date_format = $CI->config->item('date_format');
			if($string != '0000-00-00' && $string != '' && $string != NULL)
				$Date = date($date_format, strtotime($string));
			else
				$Date = '';
			
				if($echo)
					return $Date;
				else
					echo $Date;
			
		}

	}
	if (!function_exists('_viewmonth')) {
		function _viewmonth ($string,$echo=false) {       
			$CI =& get_instance();
			$date_format = $CI->config->item('date_format');
			if($string != '0000-00-00' && $string != '' && $string != NULL)
				$Date = date("F, Y", strtotime($string));
			else
				$Date = '';
			
				if($echo)
					return $Date;
				else
					echo $Date;
			
		}

	}
	if (!function_exists('_viewtime')) {
		function _viewtime ($string,$echo=false) {       
				$CI =& get_instance();
				$time_format = $CI->config->item('time_format');			
				if($string != '0000-00-00 00:00:00' && $string != '' && $string != NULL)
					$Time = date($time_format, strtotime($string));
				else
					$Time = '';
			
				if($echo)
					return $Time;
				else
					echo $Time;
				
			
		}

	}
	if (!function_exists('_viewdatetime')) {
		function _viewdatetime ($string,$echo=false) {
			$CI =& get_instance();       
			$date_time_format = $CI->config->item('date_time_format');
			
			if($string != '0000-00-00 00:00:00' && $string != '' && $string != NULL)
				$DateTime =  date($date_time_format, strtotime($string));
			else
				$DateTime = '';
				
				if($echo)
					return $DateTime;
				else
					echo $DateTime;
			
		}

	}	
	if (!function_exists('getConfigValue')) {
		function getConfigValue($field = ""){			
			$ci =& get_instance();
			$ci->load->database();
			$sql = "select * from `general_config` where field ='".$field."'";
			$q = $ci->db->query($sql);			 
			$result = $q->row_array();
			return $result['value'];
		}
	}
	if (!function_exists('_getToday')) {
		function _getToday ($echo=false) {	
				$DateTime = date(getConfigValue('date_format').' '.getConfigValue('time_format'));
				
				if(!$echo)
					return $DateTime;
				else
					echo $DateTime;			
		}
	}
	
	if (!function_exists('insertPoint')) {
			function insertPoint($data, $ref_id=0,$action_date=''){
				$ci =& get_instance();
				$ci->load->database();
				if(isset($data['type']))
				{
					$sql = "select * from point_master where point_type ='".$data['type']."'";
				}
				else
				{
					$sql = "select * from point_master where point_id ='".$data['id']."'";
				}		
				$q = $ci->db->query($sql);			 
				if($result = $q->row_array())
				{
					if($result['status'] == 'Y'){
						$data = array(
						   'member_id' => $data['member_id'] ,
						   'point_id' => $result['point_id'] ,
						   'points' =>  $result['point'],
						   'ref_id' =>  $ref_id,
						   'created_date' => date('Y-m-d H:i:s'),
						   'action_date' => $action_date?$action_date:date('Y-m-d')
						);				
						$finalResult = $ci->db->insert('member_points_log', $data); 
						//echo $ci->db->last_query(); exit;
					};
					
				}
				else
				{
					$finalResult = $result;
				}
			return $finalResult;
		}
	}
	?>
