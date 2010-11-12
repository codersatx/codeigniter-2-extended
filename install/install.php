<?php

	$path = dirname(__FILE__);
	
	## Install Phake to /usr/bin/phake
	system('rm -rf /usr/bin/phake');
	system('cp -rf '.$path.'/phake /usr/bin/phake');
	
	echo "\nInstallation complete!\n- Add the following directory to your PATH to use phake: /usr/bin/phake\n\n";
	
?>