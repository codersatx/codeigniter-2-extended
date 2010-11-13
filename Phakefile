<?php
	
	## Database
	group('db', function() {
		
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

?>