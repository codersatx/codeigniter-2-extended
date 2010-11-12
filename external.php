<?php

	error_reporting(E_ALL);
	date_default_timezone_set('GMT');
	
	$system_path = "system";
	$application_folder = "application";

	if (realpath($system_path) !== FALSE)
	{
		$system_path = realpath($system_path).'/';
	}

	$system_path = rtrim($system_path, '/').'/';

	if ( ! is_dir($system_path))
	{
		exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
	}

	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
	define('EXT', '.php');
	define('BASEPATH', str_replace("\\", "/", $system_path));
	define('FCPATH', str_replace(SELF, '', __FILE__));
	define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));

	if (is_dir($application_folder))
	{
		define('APPPATH', $application_folder.'/');
	}
	else
	{
		if ( ! is_dir(BASEPATH.$application_folder.'/'))
		{
			exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
		}

		define('APPPATH', BASEPATH.$application_folder.'/');
	}

		define('CI_VERSION', '2.0');
		require(BASEPATH.'core/Common'.EXT);
		require(BASEPATH.'core/Compat'.EXT);
		require(APPPATH.'config/constants'.EXT);
		

		set_error_handler('_exception_handler');

		if ( ! is_php('5.3'))
		{
			@set_magic_quotes_runtime(0); // Kill magic quotes
		}

		if (isset($assign_to_config['subclass_prefix']) AND $assign_to_config['subclass_prefix'] != '')
		{
			get_config(array('subclass_prefix' => $assign_to_config['subclass_prefix']));
		}

		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
		{
			@set_time_limit(300);
		}

		if (is_php('5.0.0') == TRUE)
		{
			require(BASEPATH.'core/Base5'.EXT);
		}
		else
		{
			// The Loader class needs to be included first when running PHP 4.x
			load_class('Loader', 'core');
			require(BASEPATH.'core/Base4'.EXT);
		}
		
		require BASEPATH.'core/Controller'.EXT;
		$CI = new CI_Controller();

/* End of file external.php */
/* Location: ./external.php */