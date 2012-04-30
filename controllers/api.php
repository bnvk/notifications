<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct();
        
		// Config Email	
		$this->load->library('email');
		
		$config_email['protocol']  	= config_item('services_email_protocol');
		$config_email['mailtype']  	= 'html';
		$config_email['charset']  	= 'UTF-8';
		$config_email['crlf']		= '\r\n';
		$config_email['newline'] 	= '\r\n'; 			
		$config_email['wordwrap']  	= FALSE;
		$config_email['validate']	= TRUE;
		$config_email['priority']	= 1;
			
		if (config_item('services_email_protocol') == 'smtp')
		{			
			$config_email['smtp_host'] 	= config_item('services_smtp_host');
			$config_email['smtp_user'] 	= config_item('services_smtp_user');
			$config_email['smtp_pass'] 	= config_item('services_smtp_pass');
			$config_email['smtp_port'] 	= config_item('services_smtp_port');
		}

		$this->email->initialize($config_email);
	}

    /* Install App */
	function install_get()
	{
		// Load
		$this->load->library('installer');
		$this->load->config('install');        

		// Settings & Create Folders
		$settings = $this->installer->install_settings('notifications', config_item('notifications_settings'));
	
		if ($settings == TRUE)
		{
            $message = array('status' => 'success', 'message' => 'Yay, the Notifications was installed');
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Dang Notifications could not be uninstalled');
        }		
		
		$this->response($message, 200);
	} 
	
	function blast_authd_post()
	{
		// Loop Through Methods Sent (mobile, sms, email)
		$notification_subject	= $this->input->post('notification_subject');		
		$notification_message	= $this->input->post('notification_message');
	

		// Get Users who have those methods set
		if ($users = $this->social_auth->get_users('active', 1, TRUE))
		{		
			foreach ($users as $user)
			{
				if ($this_user_meta = $this->social_auth->get_user_meta_module($user->user_id, 'notifications'))
				{
					$frequency	= $this->social_auth->find_user_meta_value('notifications_frequency', $this_user_meta);
					$do_email	= $this->social_auth->find_user_meta_value('notifications_email', $this_user_meta);
					$do_sms		= $this->social_auth->find_user_meta_value('notifications_sms', $this_user_meta);
					$do_mobile	= $this->social_auth->find_user_meta_value('notifications_mobile', $this_user_meta);

	
					// Check Is Last Notification Is Not Too Soon
					if ($frequency != 'none')
					{
						$output = '';
					
						// Do Email
						if ($do_email == 'yes')
						{
							//$message = $this->load->view(config_item('email_templates').config_item('email_signup'), $data, true);
				
							$this->email->from(config_item('site_admin_email'), config_item('site_title'));
							$this->email->to($user->email);
							$this->email->subject($notification_subject);
							$this->email->message($notification_message);
							$this->email->send();							
						}
					
						// Do Mobile PuSH
						if ($do_mobile == 'yes')
						{
							$output .= 'Do PuSH';
						}						
						
						// Do SMS
						if ($do_sms == 'yes')
						{
							// Send a new outgoing SMS */
							$this->load->config('twilio/twilio');
							$this->load->library('twilio/twilio');			
							
							$from 		= config_item('twilio_phone_number');
							$to			= $user->phone_number;
							$message 	= $notification_message;
					
							$this->twilio->sms($from, $to, $message);						
						}
					}
				}
			}
		
		}
		
		// Check User Frequency / Last Message Sent
		
		
		
		// Send Message To Users		
		if ($output)
		{
            $message = array('status' => 'success', 'message' => 'Yay, the Notifications was installed');
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Dang Notifications could not be uninstalled');
        }		
		
		$this->response($message, 200);		
	}

}