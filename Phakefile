<?php
	
	## Database
	group('db', function() {
		
		desc('Create the database listed in application/config/database.php');
		task('create', function() {
			include_once('external.php');
			$CI->migration->create_database();
		});
		
		desc('Generate default migration file - application/migrations/unix_timestamp.xml');
		task('create_migration', function() {
			include_once('external.php');
			$CI->migration->create_migration();
		});
		
		desc('Run all new migrations');
		task('migrate', function() {
			include_once('external.php');
			$CI->migration->run();
		});
		
		desc('Reset schema_migrations table and re-run all migrations');
		task('reset', function() {
			include_once('external.php');
			$CI->migration->reset();
		});
		
	});
	
	group('system', function() {
	
		desc('Install the example.');
		task('install', function() {
			include_once('external.php');
			include_once('examples/model_test/install.php');
		});
		
		desc('Uninstall the example.');
		task('uninstall', function() {
			include_once('external.php');
			include_once('examples/model_test/uninstall.php');
		});
		
		desc('Run the example.');
		task('run', function() {
			include_once('external.php');
			include_once('examples/model_test/run.php');
		});
		
	});
	
?>