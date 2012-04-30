<?php
class Home extends Dashboard_Controller
{
    function __construct()
    {
        parent::__construct();

		$this->load->config('config');

		$this->data['page_title'] = 'Notifications';
	}
	
	function blast()
	{
		$this->data['sub_title'] = 'Blast';
	
		$this->render();
	}
}