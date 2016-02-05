<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Email_lib {

    /**
     * **********************************************************************************
     * @package    Manage users
     * @version    1.0
     * @author     JIBIN JOY
     * @copyright  2015 Newagesmb (http://www.newagesmb.com), All rights reserved.
     * Created on  06-Jan-2015
     * 
     * This library is written solemnly for the purpose of user authentication
     * feature for the elek realty web project. Unauthorized editing or reuse is 
     * not entertained.
     * 
     * ********************************************************************************* */
    protected $CI;

    public function __construct() {
        // Do something with $params
        $this->CI = &get_instance();
        $this->CI->load->helper('url');
		$this->CI->load->model('email_model');
		$this->CI->load->library('email');
    }



    function registrationSuccessMailToAdmin($var) {		
        $useremail = $var['email'];		
        $email = $this->CI->email_model->get_email_template("register_email");		
        $subject = $email['email_subject'];        
        $message = $email['email_template'];		
        $message = str_replace('#FULL_NAME#', $var['name'], $message);
        $message = str_replace('#EMAIL#', $var['email'], $message);
		if(isset($var['country']))
				$message = str_replace('#COUNTRY#', $var['country'], $message);
			else
				$message = str_replace('#COUNTRY#', 'Not Available', $message);
		if(isset($var['industry']))
				$message = str_replace('#INDUSTRY#', $var['industry'], $message);
			else
				$message = str_replace('#INDUSTRY#', 'Not Available', $message);
		if(isset($var['level']))
				$message = str_replace('#EXPERIENCE#', $var['level'], $message);
			else
				$message = str_replace('#EXPERIENCE#', 'Not Available', $message);
		
		$message = str_replace('#SITE_NAME#', getConfigValue("site_name"), $message);		
		$to = getConfigValue("email_from");		
        $this->_send($to, $subject, $message);
    }

    function registrationSuccessMailToUser($var) {		
        $useremail = $var['email'];		
        $email = $this->CI->email_model->get_email_template("register_email_touser");		
        $subject = $email['email_subject'];        
        $message = $email['email_template'];		
        $message = str_replace('#FULL_NAME#', $var['name'], $message);
		//$message = str_replace('#SITE_NAME#', getConfigValue("site_name"), $message);		
        $this->_send($useremail, $subject, $message);
    }


    function _send($to, $subject, $message) {		
		$from = getConfigValue("email_from");		
		$this->CI->email->set_mailtype("html");
		$this->CI->email->from($from);	
		$this->CI->email->to($to);
		$this->CI->email->subject($subject);
		$this->CI->email->message($message);
		$this->CI->email->send();
    }
   
}