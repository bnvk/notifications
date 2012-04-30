<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:		Social Igniter : Notifications : Config
* Author: 	Brennan Novak
* 		  	contact@social-igniter.com
* 
* Created by Brennan Novak
*
* Project:	http://social-igniter.com
* Source: 	http://github.com/socialigniter/module-template
* 
* Description: this file Social Igniter
*/

$config['notifications_path']		= 'notifications/';
$config['notifications_frequency']	= array(
	'none'		=> '---select---',
	'daily_3'	=> '3 x Day',
	'daily_1'	=> '1 x Day',
	'daily_alternate' => 'Every Other Day',
	'weekly'	=> 'Weekly',
	'weekly_alternate' => 'Every Other Week',
	'never'		=> 'Never'
);