<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_model extends CI_Model {
	
	function __construct()
    {
        parent::__construct();
    }

    function notify_email($task = '' , $param2 = '' , $param3 = '' , $param4 = '' , $param5 = '')
    {
    	$email_sub		=	$this->db->get_where('email_template' , array('task' => $task))->row()->subject;
    	$email_msg		=	$this->db->get_where('email_template' , array('task' => $task))->row()->body;

        if ($task == 'new_user_account_opening')
        {
            $user_id      =   $param2;
            $USER_PASSWORD=   $param3;
            $USER_NAME    =   $this->db->get_where('user' , array('user_id' => $user_id))->row()->name;
            $USER_EMAIL   =   $this->db->get_where('user' , array('user_id' => $user_id))->row()->email;
            $SYSTEM_URL     =   base_url();

            $email_msg      =   str_replace('[USER_NAME]' , $USER_NAME , $email_msg);
            $email_msg      =   str_replace('[SYSTEM_URL]' , $SYSTEM_URL , $email_msg);
            $email_msg      =   str_replace('[USER_EMAIL]' , $USER_EMAIL , $email_msg);
            $email_msg      =   str_replace('[USER_PASSWORD]' , $USER_PASSWORD , $email_msg);
            
            $email_to       =   $USER_EMAIL;
            $this->do_email($email_msg , $email_sub , $email_to);
        }

        if ($task == 'new_user_account_confirm')
        {
            $user_id      =   $param2;
            $USER_NAME    =   $this->db->get_where('user' , array('user_id' => $user_id))->row()->name;
            $USER_EMAIL   =   $this->db->get_where('user' , array('user_id' => $user_id))->row()->email;
            $SYSTEM_URL     =   base_url();

            $email_msg      =   str_replace('[USER_NAME]' , $USER_NAME , $email_msg);
            $email_msg      =   str_replace('[SYSTEM_URL]' , $SYSTEM_URL , $email_msg);
            
            $email_to       =   $USER_EMAIL;
            $this->do_email($email_msg , $email_sub , $email_to);
        }
}

	function do_email($msg = NULL, $sub = NULL, $to = NULL, $from = NULL, $attachment_url = NULL)
    {
         $config = array(
            'protocol' =>  $this->db->get_where('settings', array('type' => 'protocol'))->row()->description,
            'smtp_host' => $this->db->get_where('settings', array('type' => 'smtp_host'))->row()->description,
            'smtp_user' => $this->db->get_where('settings', array('type' => 'smtp_user'))->row()->description, 
            'smtp_pass' => $this->db->get_where('settings', array('type' => 'smtp_pass'))->row()->description,
            'smtp_port' => $this->db->get_where('settings', array('type' => 'smtp_port'))->row()->description,
            'smtp_crypto' => $this->db->get_where('settings', array('type' => 'smtp_crypto'))->row()->description,
            'mailtype' =>  $this->db->get_where('settings', array('type' => 'mailtype'))->row()->description,
            'wordwrap' => TRUE,
            'charset' => $this->db->get_where('settings', array('type' => 'charset'))->row()->description);

                $system_name = $this->db->get_where('settings', array('type' => 'system_name'))->row()->description;
                $from = $this->db->get_where('settings', array('type' => 'smtp_user'))->row()->description;
                $this->email->set_mailtype('html');
                $this->load->library('email', $config);
                $this->email->set_newline("\r\n");
                if ($attachment_url != NULL)
                $this->email->attach($attachment_url);
                $this->email->from($from,$system_name);
                $this->email->to($to);
                $this->email->subject($sub);
                $this->email->message($msg);
                $this->email->send();
    }
}